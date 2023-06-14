<template>
  <div class="tag" :class="[`tag-${variant}`, `tag-${size}`, { active }]">
    <span>
      <slot />
    </span>
  </div>
</template>

<script setup lang="ts">
type Variant = 'text' | 'button'
type Size = 'big' | 'small'

withDefaults(
  defineProps<{
    variant?: Variant
    size?: Size
    active?: boolean
  }>(),
  {
    variant: 'text',
    size: 'big',
    active: false
  }
)
</script>

<style src="./tag.css" scoped></style>
