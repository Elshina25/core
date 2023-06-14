import { reactive, ref } from 'vue'
import uniqueId from 'lodash-es/uniqueId'

interface Config<T> {
  values: T
  rules: Partial<Record<keyof T, string>>
  errors: Partial<Record<keyof T, string>>
}

/**
 * Решение для использования vee-validate в формах
 * @param config
 * @returns
 *
 * @example
 * ```ts
 * const { form, pending, setErrors } = useForm({
 *   values: {
 *     name: '',
 *     phone: '',
 *     company: '',
 *     email: ''
 *   },
 *   rules: {
 *     name: 'required',
 *     phone: 'required|phone',
 *     company: 'required',
 *     email: 'required|email'
 *   },
 *   errors: {}
 * })
 * ```
 */
export const useForm = <T>(config: Config<T>) => {
  const defaultValue = ({ ...config.values } ?? {}) as Config<T>['values']

  const values = reactive(config.values ?? {}) as Config<T>['values']
  const rules = reactive(config.rules ?? {}) as Config<T>['rules']
  const errors = reactive(config.errors ?? {}) as Config<T>['errors']

  // Идентификатор формы
  const key = ref(uniqueId('form-'))

  /**
   * Записать ошибки валидации
   * @param newErrors
   */
  const setErrors = (newErrors: Config<T>['errors'] | null) => {
    for (const field in values) {
      errors[field] = newErrors ? newErrors?.[field] ?? '' : ''
    }
  }

  /**
   * Ожидание (процесс загрузки)
   */
  const pending = reactive({
    status: false,
    start: () => (pending.status = true),
    stop: () => (pending.status = false)
  })

  /**
   * Сбросить форму
   */
  const resetForm = () => {
    for (const key in values) {
      values[key] = defaultValue[key]
    }

    key.value = uniqueId('form-')
  }

  return {
    form: {
      values,
      rules,
      errors,
      key
    },
    resetForm,
    setErrors,
    pending
  }
}
