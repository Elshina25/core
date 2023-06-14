<template>
  <section class="mb-80 md:mb-100 2xl:mb-[165px]">
    <div class="clients">
      <div class="container t-h1 mb-30 md:mb-40 2xl:mb-60">
        Наши ключевые клиенты
      </div>
      <about-clients-row :items="firstRow" :sec-per-client="7" />
      <about-clients-row :items="secondRow" :sec-per-client="6" />
      <about-clients-row :items="thirdRow" :sec-per-client="9" />
    </div>
  </section>
</template>

<script setup lang="ts">
import AboutClientsRow from '@/pages/about/components/AboutClientsRow.vue'
import { useClientStore } from '@/stores/clients'
import { storeToRefs } from 'pinia'

const store = useClientStore()
const { items } = storeToRefs(store)

await store.fetchList()

const rowCount = Math.floor(items.value.length / 3)
const firstRow = items.value.slice(0, rowCount)
const secondRow = items.value.slice(rowCount, rowCount * 2)
const thirdRow = items.value.slice(rowCount * 2, items.value.length)
</script>
