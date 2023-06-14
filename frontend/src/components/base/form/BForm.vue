<template>
  <form :class="{ form: !clearClass }" @submit.prevent="onSubmit">
    <slot />
  </form>
</template>

<script setup lang="ts">
import { watch } from 'vue'
import { IForm } from '@/types/ui'
import { useField, useForm } from 'vee-validate'

const emit = defineEmits<{
  (e: 'success'): void
  (e: 'errors', event: IForm['errors']): void
}>()

const props = withDefaults(
  defineProps<{
    form: IForm
    clearClass?: boolean
  }>(),
  {
    clearClass: false
  }
)

/**
 * Установим поля формы
 * с наблюдателем за изменением значений
 */
const setFields = () => {
  for (const field in props.form.values) {
    const { handleChange } = useField(field)

    // Наблюдатель за значением поля
    watch(
      () => props.form.values[field],
      (newValue) => {
        handleChange(newValue)
      }
    )
  }
}

const form = useForm({
  validationSchema: props.form.rules,
  initialValues: props.form.values
})
setFields()

/**
 * Сообщаем об успешной отправке формы
 */
const onSubmit = form.handleSubmit(() => {
  emit('success')
})

/**
 * Сообщаем об ошибках
 */
watch(form.errors, () => {
  if (form.meta.value.touched) {
    emit('errors', form.errors.value as IForm['errors'])
  }
})

/**
 * Сбросить форму, если идентификатор изменился
 */
watch(props.form.key, () => {
  form.resetForm()
})
</script>

<style scoped></style>
