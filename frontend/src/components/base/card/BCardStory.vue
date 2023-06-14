<template>
  <div class="card" :class="`card--${variant}`">
    <div class="card-badges">
      <b-badge
        v-for="(badge, idx) in item.topics"
        :key="idx"
        variant="outline-gray"
        >{{ badge }}</b-badge
      >
    </div>

    <person-item
      class="card-author"
      :variant="authorVariant"
      :person="item.author"
    />

    <div class="card-text" v-html="textWithQuotes"></div>

    <template v-if="variant === 'slider'">
      <a
        class="card-link link"
        :href="`${$ROUTE.VACANCY.slug}/employee-stories`"
        >Читать полностью</a
      >
      <div class="card__bg-2"></div>
      <div class="card__bg-3"></div>
    </template>
  </div>
</template>

<script lang="ts" setup>
import { computed, ComputedRef } from 'vue'
import { IStory } from '@/types'
import BBadge from '@/components/base/badge/BBadge.vue'
import PersonItem from '@/components/person/PersonItem.vue'

type Variant = 'slider' | 'default'

const props = withDefaults(
  defineProps<{
    item: IStory
    variant?: Variant
  }>(),
  {
    variant: 'default'
  }
)

const authorVariant: ComputedRef<'vacancy' | 'story'> = computed(() =>
  props.variant === 'slider' ? 'vacancy' : 'story'
)

// Регулярное выражение проверяет на наличии скобки в начале
const startQuoteRegex = new RegExp(/^«/)

// Регулярное выражение проверяет на наличии скобки/точки/скобки с точкой в конце
const endQuoteRegex = new RegExp(/(»\.|\.|»)$/)

const startQuote = '<span class="text-green">«</span>'
const endQuote = '<span class="text-green">».</span>'

const textWithQuotes = computed(() => {
  let textToQuote = props.item.previewText

  // Если скобка уже есть - меняем на зеленую, если нет - то добавляем
  textToQuote = textToQuote.match(startQuoteRegex)
    ? textToQuote.replace(startQuoteRegex, startQuote)
    : startQuote + textToQuote

  textToQuote = textToQuote.match(endQuoteRegex)
    ? textToQuote.replace(endQuoteRegex, endQuote)
    : textToQuote + endQuote

  return textToQuote
})
</script>

<style scoped lang="postcss">
.card {
  @apply bg-white rounded xl:rounded-[40px] p-16 md:p-30 xl:p-60 xl:pt-[108px] relative;
  @apply flex flex-col xl:flex-row gap-10 md:gap-20 xl:gap-40;

  &-author {
    @apply order-1 xl:order-2 md-only:max-w-[460px] lg-only:max-w-[460px] xl:min-w-[340px] xl:mt-6;
  }

  &-text {
    @apply order-2 xl:order-1 t-p2 md:t-p1;
  }

  &-badges {
    @apply absolute flex gap-4 md:gap-8 xl:gap-10 h-fit;
    @apply top-16 md:top-30 xl:top-60 mobile:right-16 md-only:right-30 lg-only:right-30 xl:left-60;
  }

  &-link {
    @apply t-p2 order-3 w-fit absolute bottom-16 left-16 xl:left-30 xl:bottom-30;
  }

  &--slider {
    @apply w-[266px] h-[373px] md:w-[716px] xl:w-[645px] xl:h-[590px]
           md:p-16 xl:p-30 md:pt-[59px] xl:pt-[78px]
           mobile:mr-30 md:mr-60 xl:mr-0
           xl:flex-col xl:gap-20 xl:rounded;

    > .card-badges {
      @apply mobile:flex-col mobile:items-end md:left-16 md:top-16 xl:left-30 xl:top-30;
    }

    > .card-author {
      @apply xl:!order-1 xl:mt-0;
    }

    > .card-text {
      @apply overflow-hidden;
      display: -webkit-box !important;
      -webkit-box-orient: vertical;
      -webkit-line-clamp: 5;

      @screen md-only {
        -webkit-line-clamp: 4;
      }

      @screen lg-only {
        -webkit-line-clamp: 4;
      }

      @screen xl {
        @apply !order-2;
      }
    }
  }

  &__bg-2 {
    @apply w-full absolute bg-[#7ED0AD] -z-[10] rounded;
    @apply -right-8 top-8 bottom-8 xl:hidden;

    @screen md {
      @apply -right-12 top-12 bottom-12;
    }
  }

  &__bg-3 {
    @apply w-full absolute bg-green -z-[20] rounded;
    @apply -right-16 top-16 bottom-16 xl:hidden;

    @screen md {
      @apply -right-20 top-24 bottom-24;
    }
  }
}
</style>
