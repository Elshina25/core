<template>
  <slider-awards :items="awards" />
</template>

<script setup lang="ts">
import SliderAwards from '@/components/slider/SliderAwards.vue'
import { IAward } from '@/types'

defineProps<{
  awards: IAward[]
}>()
</script>
