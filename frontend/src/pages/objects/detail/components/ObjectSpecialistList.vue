<template>
  <section class="authors lg:min-w-[420px] lg:max-w-[420px] w-full">
    <h2 class="lg:t-h3">Получить консультацию</h2>

    <div class="authors__list">
      <person-item
        v-for="(item, idx) in persons"
        :key="idx"
        :person="item"
        class="mb-20 md:mb-0 lg:mb-30 md-only:w-[304px]"
      />
    </div>
  </section>
</template>

<script lang="ts" setup>
import { IPerson } from '@/types'
import PersonItem from '@/components/person/PersonItem.vue'

defineProps<{
  persons: IPerson[]
}>()
</script>

<style lang="postcss" scoped>
.authors {
  @apply mb-40 md-only:mt-80 lg:mb-0 mobile:pb-12 md-only:pb-40;

  &__list {
    @apply mt-28 md:mt-36 lg:mt-30;
    @screen md-only {
      @apply flex flex-wrap gap-x-24 gap-y-30;
    }
  }
}
</style>
