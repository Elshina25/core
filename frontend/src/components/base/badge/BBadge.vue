<template>
  <div class="badge" :class="`badge-${variant}`">
    <slot />
  </div>
</template>

<script setup lang="ts">
type Variant = 'white' | 'outline-gray' | 'grey' | 'outline-green' | 'green'

withDefaults(
  defineProps<{
    variant?: Variant
  }>(),
  {
    variant: 'white'
  }
)
</script>

<style src="./badge.css" scoped></style>
