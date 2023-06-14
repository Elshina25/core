<template>
  <b-form :form="form" class="form" @success="onSubmit" @errors="setErrors">
    <div class="form__text">
      <h2 class="lg:t-h3 mb-8 md:mb-10">{{ title }}</h2>
      <p class="t-p2">{{ subtitle }}</p>
    </div>

    <div class="form__input">
      <b-input
        v-model="form.values.email"
        :error-message="form.errors.email"
        :disabled="pending.status"
        mode="clear"
        type="email"
        name="email"
        label="Эл. почта"
      />

      <b-input
        v-model="form.values.check"
        class="hidden"
        type="text"
        name="check"
        label=""
      />

      <form-select
        v-model="form.values.sections"
        :options="sectionsOptions"
        :error-message="form.errors.sections"
        :disabled="pending.status"
        name="estate"
        label="Вид недвижимости"
      />

      <div>
        <b-button
          class="mb-8 lg:mb-10"
          variant="white-blue"
          :transparent="true"
        >
          Отправить заявку
        </b-button>

        <div class="t-caption w-4/5 md:w-3/4">
          Оставляя заявку, вы&nbsp;соглашаетесь
          <a :href="AGREEMENT_LINK" target="_blank" class="link link-white">
            с&nbsp;условиями обработки
          </a>
          личных данных
        </div>
      </div>
    </div>
    <b-modal v-model="modalSuccess.status">
      <modal-form-success />
    </b-modal>
  </b-form>
</template>

<script async setup lang="ts">
import { useForm } from '@/composables/useForm'
import { AGREEMENT_LINK } from '@/config/form.config'
import BButton from '@/components/base/button/BButton.vue'
import BForm from '@/components/base/form/BForm.vue'
import BInput from '@/components/base/input/BInput.vue'
import api from '@/api'
import { useModal } from '@/composables/useModal'
import FormSelect from '@/components/base/form/select/FormSelect.vue'
import { getReportCategories } from '@/pages/research/list/requests'
import { ISelectTag } from '@/types/ui'
import BModal from '@/components/base/modal/BModal.vue'
import ModalFormSuccess from '@/components/modal/ModalFormSuccess.vue'

const emit = defineEmits<{
  (e: 'success'): void
}>()

withDefaults(
  defineProps<{
    title?: string
    subtitle?: string
  }>(),
  {
    title: 'Подписка на отчёты',
    subtitle:
      'Взгляд на рынок коммерческой недвижимости изнутри от наших лучших аналитиков'
  }
)

const sectionsOptions = await getReportCategories()

const modalSuccess = useModal()

const { form, pending, setErrors, resetForm } = useForm({
  values: {
    sections: [],
    email: '',
    check: ''
  },
  rules: {
    sections: 'required',
    email: 'required|email'
  },
  errors: {}
})

const onSubmit = async () => {
  if (pending.status) return

  try {
    pending.start()

    await api.form.subscribe.research({
      email: form.values.email,
      check: form.values.check,
      'sections[]': form.values.sections.map(
        (section: ISelectTag) => section.code
      )
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
  @apply bg-cover rounded text-white w-full lg:w-[clamp(200px,30vw,390px)];
  @apply flex flex-col p-16 pb-24 md:p-26 md:pb-32 lg:p-30 lg:pb-40;
  @apply bg-[url(/images/research/form-subscribe-bg-mobile.png)]
  md:bg-[url(/images/research/form-subscribe-bg-tablet.png)] lg:bg-[url(/images/research/form-subscribe-bg.png)];

  &__text {
    @apply mb-20 md:mb-24 lg:mb-28;
  }

  &__input {
    @screen md-only {
      @apply flex flex-wrap gap-x-30;

      > div {
        @apply w-[calc(50%-15px)];
      }
    }

    > div {
      @apply mb-20 md:mb-24 last:mb-0;

      &:nth-last-child(2) {
        @apply mobile:mb-24;
      }
    }
  }
}
</style>
