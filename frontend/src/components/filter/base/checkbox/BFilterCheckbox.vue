<template>
  <label class="checkbox-group" :class="checkboxClasses">
    <slot name="before"></slot>
    <span v-if="variant === 'default'" class="checkbox-group__checkmark">
      <b-icon v-show="isChecked" name="check-large" />
    </span>
    <input
      class="hidden"
      type="checkbox"
      :checked="isChecked"
      :value="value"
      :disabled="disabled"
      tabindex="1"
      @change="updateInput"
    />
    {{ label }}
  </label>
</template>

<script lang="ts" setup>
import BIcon from '@/components/base/icon/BIcon.vue'

import type { CheckboxVariant, CheckboxValue } from '@/types/ui'
import { computed, toRefs } from 'vue'

const emit = defineEmits<{
  (e: 'update:modelValue', event: CheckboxValue | CheckboxValue[]): void
}>()

const props = withDefaults(
  defineProps<{
    modelValue?: CheckboxValue | CheckboxValue[]
    allowEmpty?: boolean
    label?: string
    value?: CheckboxValue
    falseValue?: CheckboxValue
    disabled?: boolean
    variant?: CheckboxVariant
    checked?: boolean | null
  }>(),
  {
    label: '',
    disabled: false,
    variant: 'default',
    checked: null,
    value: null,
    falseValue: null,
    modelValue: null,
    type: 'checkbox',
    allowEmpty: true
  }
)

const { modelValue, value, falseValue, checked, variant, allowEmpty } =
  toRefs(props)

const isChecked = computed<boolean>(() => {
  if (modelValue.value instanceof Array) {
    // @ts-ignore
    return modelValue.value.includes(value.value)
  }
  return checked.value ?? modelValue.value === value.value
})

const checkboxClasses = computed<Array<unknown>>(() => [
  `checkbox-group--${variant.value}`,
  { 'checkbox-group--checked': isChecked.value }
])

// Метод прокидывает значение чекбокса наверх
const updateInput = (e: Event) => {
  const isActive = (e.target as HTMLInputElement)?.checked
  if (modelValue.value instanceof Array) {
    // если modelValue является массивом
    const newValue: CheckboxValue[] = [...modelValue.value]
    if (isActive) {
      newValue.push(value.value)
    } else {
      newValue.splice(newValue.indexOf(value.value), 1)
    }
    emit('update:modelValue', newValue)
  } else {
    if (!isActive && !allowEmpty.value) return
    emit('update:modelValue', isActive ? value.value : falseValue.value)
  }
}
</script>

<style src="./filter-checkbox.css" scoped></style>
