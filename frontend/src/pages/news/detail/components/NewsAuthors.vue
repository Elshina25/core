<template>
  <section class="authors">
    <div class="authors__list">
      <person-item
        v-for="(author, idx) in authors"
        :key="idx"
        :person="author"
        variant="project"
        class="mb-20 last:mb-0 md:mb-0 2xl:mb-32"
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
