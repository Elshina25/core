<template>
  <div class="form-container">
    <b-form clear-class :form="form" @success="onSubmit" @errors="setErrors">
      <div class="form__input-container">
        <b-input
          v-model="form.values.name"
          :error-message="form.errors.name"
          :disabled="pending.status"
          name="name"
          label="Как вас зовут?"
        />
        <b-input
          v-model="form.values.phone"
          :error-message="form.errors.phone"
          :disabled="pending.status"
          :maska="PHONE_MASKA"
          name="phone"
          label="Телефон"
          placeholder="+7"
          help-message="Перезвоним в рабочее время. Без спама."
        />
        <b-input
          v-model="form.values.email"
          :error-message="form.errors.email"
          :disabled="pending.status"
          name="email"
          type="email"
          label="Эл. почта"
        />
        <b-input
          v-model="form.values.square"
          :error-message="form.errors.square"
          :disabled="pending.status"
          name="area"
          label="Желаемая площадь, м²"
        />
        <b-input
          v-model="form.values.check"
          class="hidden"
          type="text"
          name="check"
          label=""
        />
        <b-textarea
          v-model="form.values.message"
          :error-message="form.errors.message"
          :disabled="pending.status"
          label="Ваше сообщение"
          class="col-span-2 hidden lg:block"
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
import ModalFormSuccess from '@/components/modal/ModalFormSuccess.vue'
import { useModal } from '@/composables/useModal'
import BModal from '@/components/base/modal/BModal.vue'
import api from '@/api'

const emit = defineEmits<{
  (e: 'success'): void
}>()

const props = defineProps<{
  crmId: string
}>()

const modalSuccess = useModal()

const { form, pending, setErrors, resetForm } = useForm({
  values: {
    name: '',
    phone: '',
    email: '',
    message: '',
    square: '',
    check: ''
  },
  rules: {
    square: 'required',
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

    await api.form.order.review({
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
  &-container {
    @apply bg-auxiliary-6/50 rounded-tl rounded-tr;
    @apply mobile:pt-30 mobile:pb-16 mobile:px-16;

    @screen md {
      @apply md:p-26 md:pt-40;
    }

    @screen lg {
      @apply rounded-tr-none rounded-bl;
    }

    @screen xl {
      @apply p-50;
    }

    @screen 2xl {
      @apply px-60 py-50;
    }
  }

  &__input-container {
    @apply grid grid-cols-1 gap-20 mb-30;

    @screen md {
      @apply grid grid-cols-2 gap-x-20 gap-y-22 mb-40;
    }

    @screen lg {
      @apply gap-y-24 mb-50;
    }

    :deep(.textarea) {
      @apply h-52 md:h-54 lg:h-[134px];
    }
  }

  .btn-group {
    @apply md:flex items-center mt-30 md:mt-40;

    &__btn-send {
      @apply min-w-[260px] w-[260px] mr-20;
    }

    &__caption {
      @apply t-caption mobile:mt-6 text-black/50 lg:w-1/2;
    }
  }
}
</style>
