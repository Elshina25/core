<template>
  <component :is="breadcrumbType" :href="to" class="breadcrumb-item">
    <span :class="{ link: isLink }">
      <slot />
    </span>
    <b-icon name="arrow-right" />
  </component>
</template>

<script lang="ts" setup>
import { computed } from 'vue'
import BIcon from '@/components/base/icon/BIcon.vue'

const props = defineProps<{
  to?: string
}>()

const breadcrumbType = computed<string>(() => (props.to ? 'a' : 'span'))

const isLink = computed<boolean>(() => breadcrumbType.value === 'a')
</script>

<style src="./styles/breadcrumb-item.css" scoped></style>
