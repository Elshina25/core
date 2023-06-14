import { computed, getCurrentInstance } from 'vue'

/**
 * Решение для использования v-model в кастомных компонентах
 * @example
 * ```ts
 * import { useModelValue } from '@/composables/useModelValue'
 * defineEmits(['update:modelValue'])
 * const { value } = useModelValue(props)
 * ```
 */
export const useModelValue = <T extends Record<'modelValue', unknown>>(
  props: T
) => {
  type ModelValue = T['modelValue']

  const instance = getCurrentInstance()
  if (!instance) {
    throw new Error('instance is missing')
  }

  /**
   * Значение v-model
   */
  const value = computed({
    get() {
      return props.modelValue as ModelValue
    },
    set(newValue: ModelValue) {
      if (value.value !== newValue) {
        instance.emit('update:modelValue', newValue)
      }
    }
  })

  /**
   * Задать новое значение
   * @param newValue
   */
  const setValue = (newValue: ModelValue) => {
    value.value = newValue
  }

  return { value, setValue }
}
