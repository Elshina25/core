<template>
  <div v-show="loading" class="loader"></div>
</template>

<script lang="ts" setup>
import { injectStrict } from '@/utils'
import { loadingSymbol } from './injectionKeys'

const loading = injectStrict(loadingSymbol)
</script>

<style src="./loader.css" scoped></style>
