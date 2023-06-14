<template>
  <div
    class="textarea-group"
    :class="[`textarea-group--${mode}`, { 'has-error': !!errorMessage }]"
  >
    <label v-if="label" :for="id">{{ label }}</label>
    <div class="textarea-group__el mb-[-6px] relative">
      <textarea
        :id="id"
        v-model="value"
        :placeholder="placeholder"
        :disabled="disabled"
        class="textarea"
      />
      <p v-show="helpMessage && !errorMessage" class="help-message">
        {{ helpMessage }}
      </p>
    </div>
    <p v-show="errorMessage" class="error-message">
      {{ errorMessage }}
    </p>
  </div>
</template>

<script setup lang="ts">
import uniqueId from 'lodash-es/uniqueId'
import { useModelValue } from '@/composables/useModelValue'

const props = withDefaults(
  defineProps<{
    modelValue: string
    mode?: 'white' | 'outline' | 'clear'
    label?: string
    placeholder?: string
    helpMessage?: string
    errorMessage?: string
    disabled?: boolean
  }>(),
  {
    mode: 'white',
    label: '',
    placeholder: '',
    helpMessage: '',
    errorMessage: '',
    disabled: false
  }
)

const id = uniqueId('textarea-')

const { value } = useModelValue(props)
</script>
