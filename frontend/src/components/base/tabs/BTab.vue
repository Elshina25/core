<template>
  <div v-show="title === selectedTitle" class="mt-10 md:mt-20 2xl:mt-30">
    <slot />
  </div>
</template>

<script lang="ts">
export default { name: 'BTab' }
</script>

<script lang="ts" setup>
import { injectStrict } from '@/utils'
import { selectedTitleSymbol } from './injectionKeys'

defineProps<{
  title: string
}>()

const selectedTitle = injectStrict(selectedTitleSymbol)
</script>
