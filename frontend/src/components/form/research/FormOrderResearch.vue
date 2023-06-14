<template>
  <b-form
    :id="id"
    :form="form"
    class="form--blue"
    :class="`form--${showOnlyOn}`"
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
        v-model="form.values.questions"
        :disabled="pending.status"
        mode="clear"
        label="Комментарий"
      />

      <b-button class="mb-8 2xl:mb-10" variant="white-blue" :transparent="true">
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
import { useForm } from '@/composables/useForm'
import { useModal } from '@/composables/useModal'
import { PHONE_MASKA, AGREEMENT_LINK } from '@/config/form.config'
import BButton from '@/components/base/button/BButton.vue'
import BForm from '@/components/base/form/BForm.vue'
import BInput from '@/components/base/input/BInput.vue'
import BTextarea from '@/components/base/textarea/BTextarea.vue'
import BModal from '@/components/base/modal/BModal.vue'
import ModalFormSuccess from '@/components/modal/ModalFormSuccess.vue'
import api from '@/api'

type Variant = 'all' | 'desktop' | 'mobile'

const emit = defineEmits<{
  (e: 'success'): void
}>()

withDefaults(
  defineProps<{
    id?: string
    title?: string
    subtitle?: string
    showOnlyOn?: Variant
  }>(),
  {
    id: '',
    title: 'Заказать исследование',
    subtitle:
      'Предоставим необходимую информацию о ситуации на рынке коммерческой недвижимости для принятия лучшего решения.',
    showOnlyOn: 'all'
  }
)

const modalSuccess = useModal()

const { form, pending, setErrors, resetForm } = useForm({
  values: {
    name: '',
    phone: '',
    companyName: '',
    email: '',
    questions: '',
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

    await api.form.order.research(form.values)

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
<style lang="postcss" scoped>
.form {
  &--desktop {
    @apply mobile:hidden;
  }

  &--mobile {
    @apply md:hidden;
  }
}
</style>
