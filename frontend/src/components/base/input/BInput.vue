<template>
  <div
    class="input-group"
    :class="[`input-group--${mode}`, { 'has-error': !!errorMessage }]"
  >
    <label v-if="label" :for="id">{{ label }}</label>
    <div class="input-group__el relative">
      <input
        v-if="client"
        :id="id"
        ref="input"
        v-model="value"
        v-maska
        :data-maska="maska"
        :name="name"
        :type="type"
        :placeholder="placeholder"
        :disabled="disabled"
        class="input"
      />
      <input
        v-else
        :id="id"
        ref="input"
        v-model="value"
        :name="name"
        :type="type"
        :placeholder="placeholder"
        :disabled="disabled"
        class="input"
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
import { Ref, onMounted, ref } from 'vue'
import { vMaska } from 'maska'
import { useModelValue } from '@/composables/useModelValue'
import uniqueId from 'lodash-es/uniqueId'

defineEmits(['update:modelValue'])

const props = withDefaults(
  defineProps<{
    name: string
    modelValue: string
    mode?: 'white' | 'outline' | 'clear'
    type?: string
    label?: string
    placeholder?: string
    helpMessage?: string
    errorMessage?: string
    disabled?: boolean
    maska?: string
  }>(),
  {
    mode: 'white',
    type: 'text',
    label: '',
    placeholder: '',
    helpMessage: '',
    errorMessage: '',
    disabled: false,
    maska: ''
  }
)

const client = ref(false)
onMounted(() => (client.value = true))

const id = uniqueId('input-')
const input: Ref<HTMLInputElement | null> = ref(null)

const { value } = useModelValue(props)
</script>
