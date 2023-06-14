<template>
  <div class="mt-80 md:mt-100 2xl:mt-150">
    <h1 class="mb-30 md:mb-40 2xl:mb-60">Вакансии ({{ items.length }})</h1>

    <div class="vacancy-list" :class="{ 'vacancy-list--expanded': isExpanded }">
      <b-card-vacancy v-for="(item, idx) in items" :key="idx" :item="item" />
    </div>

    <div
      class="vacancy__expand-btn"
      :class="buttonVisibility"
      @click="isExpanded = true"
    >
      Показать все вакансии
    </div>
  </div>
</template>

<script lang="ts" setup>
import { IVacancy } from '@/types'
import { computed, ref } from 'vue'
import BCardVacancy from '@/components/base/card/BCardVacancy.vue'

const props = defineProps<{
  items: IVacancy[]
}>()

const isExpanded = ref(false)

const buttonVisibility = computed(() => {
  if (isExpanded.value || props.items.length <= 3) return 'hidden'
  return props.items.length <= 5 ? 'md:hidden' : ''
})
</script>

<style lang="postcss" scoped>
.vacancy {
  &-list {
    @apply flex flex-col gap-20;

    :deep(.card-container) {
      @apply hidden;

      &:nth-child(-n + 3) {
        @apply flex;
      }

      @screen md {
        &:nth-child(-n + 5) {
          @apply flex;
        }
      }
    }

    &--expanded {
      :deep(.card-container) {
        @apply flex;
      }
    }
  }

  &__expand-btn {
    @apply mt-28 md:mt-40 2xl:mt-60 rounded py-16 md:py-18 bg-auxiliary-6/50 t-button text-center
    md:w-[260px] 2xl:w-[295px] hover:bg-green hover:text-white cursor-pointer;
  }
}
</style>
