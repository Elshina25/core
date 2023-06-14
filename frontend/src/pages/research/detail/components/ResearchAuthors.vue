<template>
  <section class="authors">
    <h3 class="t-h3">Авторы</h3>
    <div class="authors__list">
      <person-item
        v-for="(author, idx) in authors"
        :key="idx"
        :person="author"
        variant="research"
        class="mb-20 md:mb-0 xl:mb-32"
      />
    </div>
  </section>
</template>

<script lang="ts" setup>
import { IPerson } from '@/types'
import PersonItem from '@/components/person/PersonItem.vue'

defineProps<{
  authors: IPerson[]
}>()
</script>

<style lang="postcss" scoped>
.authors {
  @apply hidden md:block mb-40 xl:mb-0 mobile:pb-12 md:pb-40 xl:pb-0 border-b border-b-auxiliary-6/50 xl:border-none xl:ml-68;

  &__list {
    @apply mt-20 xl:mt-30 device:grid device:grid-cols-3 device:gap-x-30;
  }
}
</style>
