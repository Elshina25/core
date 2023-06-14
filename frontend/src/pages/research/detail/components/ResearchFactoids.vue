<template>
  <div class="factoids">
    <div v-for="(item, idx) in items" :key="idx" class="factoids__item">
      <div class="flex items-center">
        <b-icon
          v-if="item.arrow !== 'none'"
          :class="`factoids__item-arrow factoids__item-arrow--${item.arrow}`"
          :name="`research-${item.arrow}`"
        />
        <div
          class="factoids__item-description"
          v-html="convertFactTitle(item.description)"
        />
      </div>
      <h4 class="factoids__item-subtitle" v-html="item.subTitle" />
      <div class="factoids__item-title" v-html="item.title"></div>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { IResearchFactoid } from '@/types'
import BIcon from '@/components/base/icon/BIcon.vue'
import { convertFactTitle } from '@/utils/index'

defineProps<{
  items: IResearchFactoid[]
}>()
</script>

<style lang="postcss" scoped>
.factoids {
  @apply flex flex-wrap mobile:grid-cols-1 grid-cols-2 gap-20 md:gap-30 xl:gap-40;

  &__item {
    @apply w-full md:max-w-[368px] xl:max-w-[calc(50%-20px)];

    &-description {
      @apply t-h2 xl:text-[30px] xl:mb-6;

      > :deep(span) {
        @apply t-h1 xl:t-article xl:text-[46px];
      }
    }

    &-subtitle {
      @apply t-h3 xl:t-h4 mb-10;
    }

    &-title {
      @apply t-p2 mobile:mt-4 md:mt-14 xl:mt-8 pr-16;
    }

    &-arrow {
      @apply w-24 h-24 md:w-26 md:h-26 xl:w-36 xl:h-36 mr-8 md:mr-12 xl:mr-16;

      &--up {
        @apply fill-additional-2;
      }

      &--down {
        @apply fill-[#E31212];
      }
    }
  }
}
</style>
