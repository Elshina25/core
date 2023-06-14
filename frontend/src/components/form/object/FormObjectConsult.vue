<template>
  <b-form :form="form" @success="onSubmit" @errors="setErrors">
    <div class="form__text">
      <h2 class="mb-12 md:mb-16 xl:mb-30">{{ title }}</h2>
      <p class="t-p2 xl:t-p1">{{ subtitle }}</p>
    </div>

    <div class="form__form">
      <h2 class="md:t-h3 xl:mt-4 mb-16 md:mb-28 xl:mb-24">Обратный звонок</h2>
      <b-input
        v-model="form.values.phone"
        :error-message="form.errors.phone"
        :disabled="pending.status"
        class="mb-20 md:mb-24"
        mode="clear"
        name="phone"
        label="Телефон"
        placeholder="+7"
        :maska="PHONE_MASKA"
      />
      <b-input
        v-model="form.values.check"
        class="hidden"
        type="text"
        name="check"
        label=""
      />
      <b-button class="mb-8 xl:mb-10" variant="white-green" transparent>
        Отправить заявку
      </b-button>

      <div class="t-caption xl:w-2/3 md:w-3/4">
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
import { PHONE_MASKA, AGREEMENT_LINK } from '@/config/form.config'
import BButton from '@/components/base/button/BButton.vue'
import BForm from '@/components/base/form/BForm.vue'
import BInput from '@/components/base/input/BInput.vue'
import BModal from '@/components/base/modal/BModal.vue'
import ModalFormSuccess from '@/components/modal/ModalFormSuccess.vue'
import { useModal } from '@/composables/useModal'
import api from '@/api'

const emit = defineEmits<{
  (e: 'success'): void
}>()

const props = withDefaults(
  defineProps<{
    title?: string
    subtitle?: string
    crmId?: string | null
  }>(),
  {
    title: 'Заказать помощь в подборе',
    subtitle:
      'Если вам нужна консультация или помощь в подборе, оставьте ваши контакты, и мы свяжемся с вами в ближайшее время',
    crmId: null
  }
)

const modalSuccess = useModal()

const { form, pending, setErrors, resetForm } = useForm({
  values: {
    phone: '',
    check: ''
  },
  rules: {
    phone: 'required|phone'
  },
  errors: {}
})

const onSubmit = async () => {
  if (pending.status) return

  try {
    pending.start()

    await api.form.order.consult({
      ...form.values,
      page: location.href,
      crmId: props.crmId
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
.form {
  @apply bg-cover rounded p-16 pb-24 md:p-26 xl:p-60 xl:pb-26 text-white;
  @apply flex mobile:flex-col justify-between md:gap-84;
  @apply bg-[url(/images/form/form-green-bg-mobile.png)]
  md:bg-[url(/images/form/form-green-bg-tablet.png)] xl:bg-[url(/images/form/form-green-bg.png)];

  &__text {
    @apply w-full mobile:mb-30;
  }

  &__form {
    @apply w-full md:min-w-[326px] md:max-w-[40%] xl:w-[360px] xl:min-w-[360px];
  }
}
</style>
