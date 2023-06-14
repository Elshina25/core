<template>
  <div
    class="skeleton"
    :class="{ 'is-animated': isAnimated }"
    :style="[!withClass ? { width, height } : {}]"
  >
    <div class="skeleton-item"></div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'

const props = withDefaults(
  defineProps<{
    withClass?: boolean
    width?: number | string
    height?: number | string
    isAnimated?: boolean
  }>(),
  {
    withClass: false,
    width: '100%',
    height: '100%',
    isAnimated: true
  }
)

const width = computed(() => (+props.width ? `${props.width}px` : props.width))
const height = computed(() =>
  +props.height ? `${props.height}px` : props.height
)
</script>

<style src="./skeleton.css" scoped></style>
