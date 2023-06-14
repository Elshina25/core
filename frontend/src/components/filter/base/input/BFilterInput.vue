<template>
  <label class="w-fit">
    <slot name="label"></slot>
    <div class="flex items-center">
      <div class="filter-element filter-element--border">
        <input v-model="value" :type="type" :placeholder="placeholder" />
      </div>
      <slot name="after"></slot>
    </div>
  </label>
</template>

<script lang="ts" setup>
import { useModelValue } from '@/composables/useModelValue'

const props = withDefaults(
  defineProps<{
    type?: string
    placeholder?: string
    modelValue: string
  }>(),
  {
    type: 'text',
    placeholder: ''
  }
)

const { value } = useModelValue(props)
</script>

<style src="./filter-input.css" scoped></style>
