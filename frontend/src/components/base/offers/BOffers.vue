<template>
  <div class="offers">
    <span class="offers__title">Площадь</span>
    <span class="offers__title">Этаж</span>
    <span class="offers__title">Арендная ставка</span>
    <template v-for="(offer, idx) in offers" :key="`square${idx}`">
      <!-- TODO Vlad: уточнить куда должна перенаправлять ссылка -->
      <a :href="offersUrl" class="link offers__value">
        {{ offer.unitSize }} м<sup>2</sup>
      </a>
      <span class="offers__value">{{ offer.unitFloorNumber }} этаж</span>
      <span
        class="offers__value"
        v-html="getPrice(+offer.unitOfferedRent)"
      ></span>
    </template>
  </div>
</template>

<script lang="ts" setup>
import type { ObjectListResponse } from '@/api/object/list'
import { numberFormatter } from '@/utils'

defineProps<{
  offers: ObjectListResponse['rooms']
  offersUrl: string
}>()

const getPrice = (price: number) => {
  return price && price > 0
    ? `${numberFormatter(price)} ₽/м<sup>2</sup>/год`
    : 'по запросу'
}
</script>

<style src="./offers.css"></style>
