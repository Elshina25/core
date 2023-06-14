<template>
  <component
    :is="research.closed ? 'div' : 'a'"
    class="card-research nested-link"
    :href="`/analytics/${research.slug}`"
    :class="{ 'card-research--gradient': !backgroundImage }"
    @click="onClick"
  >
    <img
      v-if="backgroundImage"
      :src="backgroundImage"
      :title="research.name"
      alt=""
    />

    <div class="badges">
      <b-badge v-if="research.section">{{ research.section }}</b-badge>
      <b-badge v-if="research.type">{{ research.type }}</b-badge>
    </div>

    <div class="info">
      <component :is="research.closed ? 'div' : 'span'" class="relative">
        <span class="info-title link link-white">
          {{ truncate(research.name, 70) }}
          <b-icon v-if="research.closed" class="info-lock" name="lock" />
        </span>
      </component>

      <div class="info-date">{{ dateFormatter(research.date) }}</div>
    </div>
  </component>
</template>

<script lang="ts" setup>
import { computed } from 'vue'
import { IResearch } from '@/types'
import { dateFormatter, getFullUrl, truncate } from '@/utils'
import BIcon from '@/components/base/icon/BIcon.vue'
import BBadge from '@/components/base/badge/BBadge.vue'

const emit = defineEmits<{
  (e: 'lock', event: IResearch): void
}>()

const props = defineProps<{
  research: IResearch
}>()

/**
 * Обрабатываем нажатие на карточку
 */
const onClick = () => {
  if (!props.research.closed) return

  // Событие для открытия модалки
  emit('lock', props.research)
}

const backgroundImage = computed(() => getFullUrl(props.research.image))
</script>

<style lang="postcss" scoped>
.card-research {
  @apply relative
    flex flex-col justify-between
    w-full h-[288px] p-16
    md:h-[358px] md:w-[calc(50%-10px)]
    lg:h-[420px] lg:w-[calc(50%-15px)] lg:p-30
    xl:h-[500px] xl:w-[calc(50%-15px)] xl:p-30
    bg-cover bg-center
    rounded overflow-hidden cursor-pointer;

  &--gradient {
    @apply bg-[url('/images/research/card-bg-mobile.png')]
      md:bg-[url('/images/research/card-bg-tablet.png')]
      lg:bg-[url('/images/research/card-bg.png')];
  }

  &:not(.card-research--gradient) {
    background: linear-gradient(
      180deg,
      rgba(0, 0, 0, 0) 0%,
      rgba(0, 0, 0, 0.7) 100%
    );
  }

  img {
    @apply absolute inset-0 w-full h-full object-cover object-center -z-10;
  }

  .badges {
    @apply flex flex-wrap gap-6 lg:gap-10;
  }

  .info {
    &-title {
      @apply t-h3 relative mr-8;
      word-break: break-word; /* TODO: попробовать установить глобально */
    }

    &-lock {
      @apply h-12 w-12 transition absolute -right-14 text-white/50 md:bottom-6 lg:bottom-0 xl:bottom-6;

      @screen lg {
        @apply w-20 h-20 -right-24;
      }
    }

    &-date {
      @apply mt-16 opacity-60 text-white t-date whitespace-nowrap;
    }
  }

  &:hover {
    .info-lock {
      @apply text-white;
    }
  }
}
</style>
