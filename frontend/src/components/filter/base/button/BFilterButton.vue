<template>
  <component :is="type" class="filter-btn" :class="`filter-btn--${variant}`">
    <slot name="before"></slot>
    <span><slot></slot></span>
    <slot name="after"></slot>
  </component>
</template>

<script setup lang="ts">
withDefaults(
  defineProps<{
    variant?: 'link' | 'grey' | 'green'
    type?: 'submit' | 'button' | 'div'
  }>(),
  {
    variant: 'green',
    type: 'button',
    label: ''
  }
)
</script>

<style src="./filter-button.css" scoped></style>
