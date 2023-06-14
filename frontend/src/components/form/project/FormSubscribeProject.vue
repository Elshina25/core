<template>
  <b-form
    :form="form"
    :class="`form--${variant}`"
    @success="onSubmit"
    @errors="setErrors"
  >
    <div class="form__text">
      <h2 class="form__text-title">{{ title }}</h2>
      <p class="t-p2 xl:t-p1">{{ subtitle }}</p>
    </div>

    <div>
      <h3 class="form__label">Подписаться на рассылку</h3>
      <div class="form__input">
        <div class="form__input-container">
          <b-input
            v-model="form.values.email"
            :error-message="form.errors.email"
            :disabled="pending.status"
            type="email"
            name="email"
            mode="clear"
            label="Эл. почта"
          />

          <b-input
            v-model="form.values.check"
            class="hidden"
            type="text"
            name="check"
            label=""
          />

          <b-button class="form__input-submit" variant="white-blue" transparent>
            Отправить заявку
          </b-button>
        </div>

        <div class="t-caption lg:w-2/3 md:w-3/4 mt-12">
          Оставляя заявку, вы&nbsp;соглашаетесь
          <a :href="AGREEMENT_LINK" target="_blank" class="link link-white"
            >с&nbsp;условиями обработки</a
          >
          личных данных
        </div>
      </div>
    </div>
    <b-modal v-model="modalSuccess.status">
      <modal-form-success />
    </b-modal>
  </b-form>
</template>

<script setup lang="ts">
import { useForm } from '@/composables/useForm'
import { AGREEMENT_LINK } from '@/config/form.config'
import BButton from '@/components/base/button/BButton.vue'
import BForm from '@/components/base/form/BForm.vue'
import BInput from '@/components/base/input/BInput.vue'
import BModal from '@/components/base/modal/BModal.vue'
import ModalFormSuccess from '@/components/modal/ModalFormSuccess.vue'
import { useModal } from '@/composables/useModal'

const emit = defineEmits<{
  (e: 'success'): void
}>()

type Variant = 'card' | 'full'

withDefaults(
  defineProps<{
    variant?: Variant
    title?: string
    subtitle?: string
    crmId: number
  }>(),
  {
    variant: 'full',
    title: 'Наши исследования — наша гордость',
    subtitle:
      'Подпишитесь, чтобы получать новые исследования рынка недвижимости на почту. Присылаем только полезную информацию.'
  }
)

const modalSuccess = useModal()

const { form, pending, setErrors, resetForm } = useForm({
  values: {
    email: '',
    check: ''
  },
  rules: {
    email: 'required|email'
  },
  errors: {}
})

const onSubmit = async () => {
  if (pending.status) return

  try {
    pending.start()

    //TODO: добавить апи

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
  @apply bg-cover rounded p-16 pb-24 md:p-26 xl:p-60 text-white;
  @apply w-full h-auto xl:w-[calc(100%-390px-30px)];
  @apply flex flex-col justify-between;
  @apply bg-[url(/images/form/form-blue-bg-mobile.png)]
  xl:bg-[url(/images/form/form-blue-bg.png)];

  @screen md-only {
    @apply flex-row h-[314px] gap-84 md:bg-[url(/images/form/form-blue-bg-tablet.png)];
  }

  @screen lg-only {
    @apply flex-row h-[314px] gap-64 md:bg-[url(/images/form/form-blue-bg-tablet.png)];

    .form__text {
      @apply w-3/5;
    }
  }

  &--card {
    .form__input-container {
      @apply xl:flex xl:items-end;
    }

    .form__label {
      @apply xl:hidden;
    }

    .form__text-title {
      @apply xl:t-article xl:w-4/5;
    }

    .form__input {
      .input-group {
        @apply xl:mb-0;
      }

      &-submit {
        @apply mb-8 xl:mb-0 xl:ml-24 xl:w-[260px];
      }
    }
  }

  &--full {
    @screen xl {
      @apply flex-row h-[385px] w-full;
    }

    .form__input {
      @apply xl:w-[382px];
    }

    .form__input-submit {
      @apply xl:w-[360px];
    }
  }

  &__label {
    @apply xl:t-h3 mb-16 md:mb-24 xl:pt-10;
  }

  &__text {
    @apply w-full mobile:mb-30 pr-20;

    &-title {
      @apply mb-12 md:mb-16 xl:mb-30;
    }
  }

  &__input {
    @apply w-full md-only:min-w-[326px];

    .input-group {
      @apply xl:w-[360px] mb-20 md:mb-24;
    }
  }
}
</style>
