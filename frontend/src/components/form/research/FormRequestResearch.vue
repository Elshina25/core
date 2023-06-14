<template>
  <b-form
    :form="form"
    :class="`form--${BLUE_THEME}`"
    @success="onSubmit"
    @errors="setErrors"
  >
    <div class="form__text">
      <h2 class="mb-8 md:mb-20 2xl:leading-[110%]">{{ title }}</h2>
      <p class="t-p2 md:max-w-[425px] 2xl:w-full">{{ subtitle }}</p>
    </div>

    <div class="form__col">
      <b-input
        v-model="form.values.name"
        :error-message="form.errors.name"
        :disabled="pending.status"
        mode="clear"
        name="name"
        label="Как вас зовут?"
      />
      <b-input
        v-model="form.values.phone"
        :error-message="form.errors.phone"
        :disabled="pending.status"
        mode="clear"
        name="phone"
        label="Телефон"
        placeholder="+7"
        :maska="PHONE_MASKA"
      />

      <b-input
        v-model="form.values.email"
        :error-message="form.errors.email"
        :disabled="pending.status"
        mode="clear"
        type="email"
        name="email"
        label="Эл. почта"
      />
    </div>

    <div class="form__col">
      <b-input
        v-model="form.values.companyName"
        :disabled="pending.status"
        mode="clear"
        name="company"
        label="Название компании"
      />

      <b-input
        v-model="form.values.check"
        class="hidden"
        type="text"
        name="check"
        label=""
      />

      <b-textarea
        v-model="form.values.comment"
        :error-message="form.errors.comment"
        :disabled="pending.status"
        mode="clear"
        label="Комментарий"
      />

      <b-button class="mb-8 2xl:mb-10" variant="white-blue" transparent>
        Отправить заявку
      </b-button>

      <div class="t-caption 2xl:w-2/3 md:w-3/4">
        Оставляя заявку, вы&nbsp;соглашаетесь
        <a :href="AGREEMENT_LINK" target="_blank" class="link link-white"
          >с&nbsp;условиями обработки</a
        >
        личных данных
      </div>
    </div>

    <b-modal v-model="modalSuccess.status">
      <modal-form-success />
    </b-modal>
  </b-form>
</template>

<script setup lang="ts">
import api from '@/api'
import { computed } from 'vue'
import { IResearch } from '@/types'
import { useForm } from '@/composables/useForm'
import { useModal } from '@/composables/useModal'
import { PHONE_MASKA, AGREEMENT_LINK, BLUE_THEME } from '@/config/form.config'
import BButton from '@/components/base/button/BButton.vue'
import BForm from '@/components/base/form/BForm.vue'
import BInput from '@/components/base/input/BInput.vue'
import BTextarea from '@/components/base/textarea/BTextarea.vue'
import BModal from '@/components/base/modal/BModal.vue'
import ModalFormSuccess from '@/components/modal/ModalFormSuccess.vue'

const emit = defineEmits<{
  (e: 'success'): void
}>()

const props = withDefaults(
  defineProps<{
    title?: string
    research: IResearch
  }>(),
  {
    title: 'Запросить доступ к исследованию'
  }
)

const modalSuccess = useModal()

const subtitle = computed(
  () => `Запросить доступ к исследованию «${props.research.name}».`
)

const { form, pending, setErrors, resetForm } = useForm({
  values: {
    name: '',
    phone: '',
    companyName: '',
    email: '',
    comment: '',
    check: ''
  },
  rules: {
    name: 'required',
    phone: 'required|phone',
    email: 'required|email'
  },
  errors: {}
})

const onSubmit = async () => {
  if (pending.status) return

  try {
    pending.start()

    await api.form.request.research({
      ...form.values,
      page: location.href,
      id: +props.research.id,
      title: props.research.name
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
<style src="@/assets/styles/components/formModal.css" scoped></style>
