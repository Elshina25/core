<template>
  <slot v-if="clientOnly"></slot>
</template>

<script setup lang="ts">
import { useClientOnly } from '@/composables/useClientOnly'
const clientOnly = useClientOnly()
</script>
