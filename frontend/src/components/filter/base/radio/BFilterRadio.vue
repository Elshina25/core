<template>
  <label class="radio-group">
    <span
      class="radio-group__checkmark"
      :class="{ 'radio-group__checkmark--checked': isChecked }"
    ></span>
    <input
      class="radio-group__radio"
      type="radio"
      :checked="isChecked"
      :value="value"
      @change="$emit('update:modelValue', value)"
    />
    {{ label }}
  </label>
</template>

<script lang="ts" setup>
import type { RadioValue } from '@/types/ui'
import { computed } from 'vue'

defineEmits<{
  (e: 'update:modelValue', event: RadioValue): void
}>()

const props = defineProps<{
  modelValue: RadioValue
  value: RadioValue
  label?: string
}>()

const isChecked = computed(() => props.modelValue === props.value)
</script>

<style src="./filter-radio.css" scoped></style>
