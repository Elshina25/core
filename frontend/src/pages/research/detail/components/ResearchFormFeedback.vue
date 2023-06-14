<template>
  <div class="form-container">
    <b-form
      class="feedback"
      :form="form"
      @success="onSubmit"
      @errors="setErrors"
    >
      <div class="lg:flex gap-40">
        <div class="lg:w-1/2">
          <b-input
            v-model="form.values.name"
            :error-message="form.errors.name"
            :disabled="pending.status"
            name="name"
            label="Как вас зовут?"
          />

          <div class="md:flex gap-20 mt-24">
            <b-input
              v-model="form.values.phone"
              :error-message="form.errors.phone"
              :disabled="pending.status"
              :maska="PHONE_MASKA"
              name="phone"
              label="Телефон"
              placeholder="+7"
              help-message="Перезвоним в рабочее время. Без спама."
              class="md:w-1/2"
            />
            <b-input
              v-model="form.values.email"
              :error-message="form.errors.email"
              :disabled="pending.status"
              name="email"
              type="email"
              label="Эл. почта"
              class="mobile:mt-20 md:w-1/2"
            />
            <b-input
              v-model="form.values.check"
              class="hidden"
              type="text"
              name="check"
              label=""
            />
          </div>
        </div>

        <b-textarea
          v-model="form.values.questions"
          :error-message="form.errors.questions"
          :disabled="pending.status"
          label="Ваше сообщение"
          class="mobile:mt-20 md-only:mt-24 lg:w-1/2"
        />
      </div>

      <div class="btn-group">
        <b-button
          class="btn-group__btn-send"
          variant="green"
          type="submit"
          :disabled="pending.status"
        >
          Отправить
        </b-button>

        <div class="btn-group__caption">
          Заполняя форму, вы&nbsp;соглашаетесь
          <a :href="AGREEMENT_LINK" target="_blank" class="link link-gray">
            с&nbsp;условиями обработки
          </a>
          личных данных
        </div>
      </div>
    </b-form>
    <b-modal v-model="modalSuccess.status">
      <modal-form-success />
    </b-modal>
  </div>
</template>

<script lang="ts" setup>
import BInput from '@/components/base/input/BInput.vue'
import BTextarea from '@/components/base/textarea/BTextarea.vue'
import BForm from '@/components/base/form/BForm.vue'
import BButton from '@/components/base/button/BButton.vue'

import { useForm } from '@/composables/useForm'

import { PHONE_MASKA } from '@/config/form.config'
import { AGREEMENT_LINK } from '@/config/form.config'
import api from '@/api'
import { IForm } from '@/types/ui'
import { useModal } from '@/composables/useModal'
import BModal from '@/components/base/modal/BModal.vue'
import ModalFormSuccess from '@/components/modal/ModalFormSuccess.vue'

const emit = defineEmits<{
  (e: 'success'): void
  (e: 'errors', event: IForm['errors']): void
}>()

const { form, pending, setErrors, resetForm } = useForm({
  values: {
    name: '',
    phone: '',
    email: '',
    questions: '',
    check: ''
  },
  rules: {
    name: 'required',
    phone: 'required|phone',
    email: 'required|email',
    questions: 'required'
  },
  errors: {}
})

const modalSuccess = useModal()

const onSubmit = async () => {
  if (pending.status) return

  try {
    pending.start()

    await api.form.feedback.simple({
      ...form.values,
      page: location.href
    })

    emit('success')
    modalSuccess.open()

    resetForm()
  } catch (e) {
    console.error(e)
  } finally {
    pending.stop()
  }
}
</script>

<style lang="postcss" scoped>
.form-container {
  @apply bg-auxiliary-6/50 mt-30 pt-30 pb-16 px-16 md:py-40
    md:px-26 lg:px-60 lg:py-50 rounded-[5px];

  :deep(.textarea) {
    @apply h-[106px] md:h-[134px] lg:h-[182px];
  }

  .btn-group {
    @apply md:flex items-center mt-30 md:mt-40 max-w-[530px];

    &__btn-send {
      @apply min-w-[260px] w-[260px] mr-20;
    }

    &__caption {
      @apply t-caption mobile:mt-6 text-black/50;
    }
  }
}
</style>
