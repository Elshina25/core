<template>
  <div class="dropdown-item">
    <BIcon
      v-if="icon"
      :collection="collection"
      :name="icon"
      class="dropdown-item__icon"
    /><slot>{{ text }}</slot>
  </div>
</template>

<script lang="ts" setup>
import BIcon from '@/components/base/icon/BIcon.vue'

defineProps<{
  text?: string
  icon?: string
  collection?: string
}>()
</script>

<style src="./styles/dropdown-item.css" scoped></style>
