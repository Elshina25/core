<template>
  <div class="container">
    <div class="t-h1 mb-30 lg:mb-60">Обсудить задачу</div>

    <b-form
      class="feedback"
      :form="form"
      @success="onSubmit"
      @errors="setErrors"
    >
      <div class="feedback__topics">
        <div>
          <p class="t-p2 lg:t-p3 mb-12 lg:mb-16">Что вас интересует</p>
          <form-feedback-topics v-model="activeTopic" :items="feedbackTopics" />
        </div>

        <div v-if="topicsMoreList.length">
          <p class="t-p2 lg:t-p3 mb-12 lg:mb-16">Вид недвижимости</p>
          <form-feedback-topics
            v-model="activeTopicMore"
            :items="topicsMoreList"
          />
        </div>

        <b-textarea
          v-model="form.values.comment"
          :error-message="form.errors.comment"
          :disabled="pending.status"
          mode="outline"
          label="Комментарий"
        />
      </div>

      <div class="feedback__fields">
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
          name="phone"
          label="Телефон"
          placeholder="+7"
          help-message="Перезвоним в рабочее время. Без спама."
          :maska="PHONE_MASKA"
        />

        <b-input
          v-model="form.values.companyName"
          :error-message="form.errors.companyName"
          :disabled="pending.status"
          name="company"
          label="Название компании"
        />

        <b-input
          v-model="form.values.email"
          :error-message="form.errors.email"
          :disabled="pending.status"
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

        <div class="feedback__fields-footer">
          <b-button type="submit" :transparent="true">Отправить</b-button>
          <div class="agreement">
            Оставляя заявку, вы&nbsp;соглашаетесь
            <a :href="AGREEMENT_LINK" target="_blank" class="link link-gray"
              >с&nbsp;условиями обработки</a
            >
            личных данных
          </div>
        </div>
      </div>
    </b-form>

    <b-modal v-model="modalSuccess.status">
      <modal-form-success />
    </b-modal>
  </div>
</template>

<script async setup lang="ts">
import { computed, Ref, ref, watch } from 'vue'
import { IFeedbackTopic } from '@/types'
import { useForm } from '@/composables/useForm'
import { useModal } from '@/composables/useModal'
import { PHONE_MASKA, AGREEMENT_LINK } from '@/config/form.config'
import BForm from '@/components/base/form/BForm.vue'
import BInput from '@/components/base/input/BInput.vue'
import BButton from '@/components/base/button/BButton.vue'
import BTextarea from '@/components/base/textarea/BTextarea.vue'
import FormFeedbackTopics from './FormFeedbackTopics.vue'
import BModal from '@/components/base/modal/BModal.vue'
import ModalFormSuccess from '@/components/modal/ModalFormSuccess.vue'
import api from '@/api'
import { IForm } from '@/types/ui'
import { getFeedbackTopics } from '@/pages/home/requests'

const emit = defineEmits<{
  (e: 'success'): void
  (e: 'errors', event: IForm['errors']): void
}>()

const feedbackTopics: Ref<IFeedbackTopic[]> = ref(await getFeedbackTopics())

const modalSuccess = useModal()

// Выбранная тема обращения (Аренда, продажа, инвестиции и тд)
const activeTopic: Ref<IFeedbackTopic['code'] | null> = ref(null)

// Выбранный вид недвижимости (Офисы, торговая недвижимомость и тд)
const activeTopicMore: Ref<IFeedbackTopic['code'] | null> = ref(null)

// Список подтемы (вид недвижимости) из главной темы обращения
const topicsMoreList = computed(() => {
  return (
    feedbackTopics.value.find((a) => a.code === activeTopic.value)?.more ?? []
  )
})

/**
 * Ослеживаем состояние списка подтем
 * При отображении или скрытии - обнуляем значение
 */
watch(topicsMoreList, () => (activeTopicMore.value = null))

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
    companyName: 'required',
    email: 'required|email'
  },
  errors: {}
})

const onSubmit = async () => {
  if (pending.status) return

  try {
    pending.start()

    await api.form.feedback.main({
      ...form.values,
      ...(activeTopic.value && { interest: activeTopic.value }),
      ...(activeTopicMore.value && { type: activeTopicMore.value }),
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
.feedback {
  @apply flex tablet:flex-col bg-white rounded;

  &__topics {
    @apply w-full flex flex-col gap-26;
    @apply p-16 pb-30;

    @screen md {
      @apply pt-26 px-26 pb-40;
      @apply gap-36;
    }

    @screen 2xl {
      @apply pt-44 pr-40 pl-60 pb-50;
      @apply gap-24;
    }

    :deep(.textarea) {
      @apply w-full lg:max-w-[600px] h-[124px];
    }
  }

  &__fields {
    @apply w-full lg:w-[45vw] xl:w-[540px] flex-none lg:flex-col;
    @apply bg-auxiliary-6/50 tablet:rounded-b lg:rounded-r;
    @apply flex flex-wrap gap-20 md:gap-x-20 md:gap-y-24 lg:gap-24;
    @apply p-16 pt-30;

    @screen md {
      @apply pb-26 px-26 pt-40;
    }

    @screen 2xl {
      @apply pt-44 px-60 pb-50;
    }

    :deep(.input-group) {
      @apply w-full md-only:w-[calc(50%-10px)];
    }

    &-footer {
      @apply w-full flex flex-col gap-6 lg:w-[260px] mt-10 md:mt-16 lg:mt-36;

      @screen md-only {
        @apply flex-row gap-20 items-center;
      }

      .button {
        @apply md-only:w-[260px];
      }

      .agreement {
        @apply t-caption text-black/60 md:max-w-[250px] md-only:w-fit;
      }
    }
  }
}
</style>
