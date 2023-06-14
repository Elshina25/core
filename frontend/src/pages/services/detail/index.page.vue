<template>
  <div class="container">
    <b-breadcrumb class="mb-30 2xl:mb-80">
      <b-breadcrumb-item to="/">Главная</b-breadcrumb-item>
      <b-breadcrumb-item to="/services">Услуги</b-breadcrumb-item>
      <b-breadcrumb-item>{{ service.name }}</b-breadcrumb-item>
    </b-breadcrumb>
    <div class="head" :class="headClass">
      <h1 class="head__title">{{ service.name }}</h1>
      <b-button
        class="head__btn"
        type="button"
        variant="white"
        @click="scrollTo(`#${CONSULTATION_FORM_ID}`)"
      >
        Оставить заявку
      </b-button>
    </div>
    <div class="persons">
      <person-item
        v-for="(person, idx) in service.workers"
        :key="idx"
        :person="person"
        class="persons__person"
      />
    </div>
    <div
      v-if="service.utp.length"
      class="flex flex-col xl:flex-row mb-40 md:mb-50 xl:mb-80 gap-30"
    >
      <div v-for="(item, idx) in service.utp" :key="idx" class="block">
        <div class="block__title">
          {{ item.title }}
        </div>
        <p class="block__description">
          {{ item.desc }}
        </p>
      </div>
    </div>
    <div class="text-group">
      <div class="text-group__persons">
        <person-item
          v-for="(person, idx) in service.workers"
          :key="idx"
          :person="person"
          variant="story"
          class="text-group__person"
        />
      </div>

      <div class="text-group__text">
        <template v-for="(text, idx) in separatedText" :key="idx">
          <b-article :content="text"></b-article>
          <research-banner v-if="isResearchBannerActive(idx)" />
        </template>
      </div>
    </div>
  </div>

  <div :id="CONSULTATION_FORM_ID" class="container mt-60 md:mt-80">
    <form-object-consult
      class="consultation-form"
      title="Получить консультацию"
      subtitle="Если у вас есть сомнения, свяжитесь с нами или оставьте заявку в этой форме. Мы поможем выбрать услугу, соответствующую вашим задачам и ответим на интересующие вопросы."
    />
  </div>

  <service-slider
    class="mt-60 md:mt-100 xl:mt-150"
    :items="service.doneProjects"
  />
  <div class="flex flex-col">
    <suspense>
      <form-feedback class="mt-80 md:mt-100 xl:mt-150 order-1 xl:order-0" />
    </suspense>

    <div class="container order-0 xl:order-1">
      <h2 class="t-h1 mt-80 md:mt-100 xl:mt-150 mb-30 xl:mb-60">
        Сопутствующие услуги
      </h2>

      <div class="service-other">
        <service-others
          v-for="serviceGroup in serviceList"
          :key="serviceGroup.id"
          :item="serviceGroup"
        />
      </div>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { computed } from 'vue'
import { PageProps } from './types'
import BArticle from '@/components/base/article/BArticle.vue'
import PersonItem from '@/components/person/PersonItem.vue'
import BBreadcrumbItem from '@/components/base/breadcrumb/BBreadcrumbItem.vue'
import BBreadcrumb from '@/components/base/breadcrumb/BBreadcrumb.vue'
import FormFeedback from '@/components/form/feedback/FormFeedback.vue'
import FormObjectConsult from '@/components/form/object/FormObjectConsult.vue'
import BButton from '@/components/base/button/BButton.vue'
import ServiceSlider from './components/ServiceSlider.vue'
import ServiceOthers from './components/ServiceOthers.vue'
import { scrollTo } from '@/utils'
import ResearchBanner from '@/pages/services/detail/components/ResearchBanner.vue'

const props = defineProps<PageProps>()

const serviceList = computed(() => {
  return props.service.otherService.filter((el) => el?.items?.length)
})

const headClass = computed<string>(() =>
  props.service.section?.code ? `head--${props.service.section.code}` : ''
)

const CONSULTATION_FORM_ID = 'consultation'

// Флаг отвечающий за отображение баннера в тексте
const showResearchBanner = computed<boolean>(() =>
  props.service.detailText.includes('#RESEARCH_BANNER#')
)

// Формируем массив для отображения текста и баннера
const separatedText = computed<string[]>(() =>
  props.service.detailText.split('#RESEARCH_BANNER#')
)

/**
 * Отображение баннера исследований в тексте
 * @param {number} idx
 * @returns {boolean}
 * @description метод возвращает флаг для отображения баннера.
 * Разделителем текста является ключевое слово #RESEARCH_BANNER#, которое отвечает за отображение баннера,
 * следовательно необходимо отобразить баннер после каждого элемента массива, кроме последнего
 */
const isResearchBannerActive = (idx: number): boolean => {
  return showResearchBanner.value && idx !== separatedText.value.length - 1
}
</script>

<script lang="ts">
export { onBeforeRender } from './onBeforeRender'
</script>

<style lang="postcss" scoped>
.consultation-form {
  :deep(p) {
    @apply xl:text-[24px];
  }
}
.head {
  @apply md:min-h-[342px] xl:min-h-[430px] mb-40 md:mb-30
    md:py-60 md:px-30 xl:py-80 xl:px-40 md:bg-[#E5EAED] rounded
    bg-contain bg-no-repeat bg-right-bottom;

  &--consulting {
    @apply md:bg-[url('/images/service/detail/lg/consulting.jpg')]
      xl:bg-[url('/images/service/detail/lg/consulting.jpg')];
  }

  &--accompaniment {
    @apply md:bg-[url('/images/service/detail/lg/accompaniment.jpg')]
      xl:bg-[url('/images/service/detail/lg/accompaniment.jpg')];
  }

  &--control {
    @apply md:bg-[url('/images/service/detail/lg/control.jpg')]
      xl:bg-[url('/images/service/detail/lg/control.jpg')];
  }

  &--exploitation {
    @apply md:bg-[url('/images/service/detail/lg/exploitation.jpg')]
      xl:bg-[url('/images/service/detail/lg/exploitation.jpg')];
  }

  &--selection {
    @apply md:bg-[url('/images/service/detail/lg/selection.jpg')]
      xl:bg-[url('/images/service/detail/lg/selection.jpg')];
  }

  &__title {
    @apply ttl md-only:t-h1 mb-20 xl:mb-30 uppercase;
  }

  &__btn {
    @apply w-[158px] h-40 md:w-[174px] md:h-46 xl:w-[208px] xl:h-48;
  }
}

.block {
  @apply bg-white p-16 md:p-26 xl:p-30 md-only:min-h-[186px] xl:w-[calc(33%-30px/2)] rounded;

  &__title {
    @apply t-h2 text-green md:leading-120 xl:leading-[110%];
  }

  &__description {
    @apply t-p1 xl:t-p2 xl:text-20 mt-12 xl:mt-20;
  }
}

.persons {
  @apply mb-40 md:flex md:flex-wrap md:mb-30 md:gap-24 xl:hidden;

  &__person {
    @apply mobile:mb-20;

    :deep(.person) {
      &__photo {
        @apply w-80 h-80;
      }
    }
  }
}

.text-group {
  &__text {
    @apply mobile:mt-30 order-1 xl:-order-none mb-40 md:mb-50 xl:mb-72;
  }

  &__persons {
    @apply hidden xl:block xl:float-right
      xl:ml-100 xl:w-[420px];
  }

  &__person {
    @apply xl:mb-30;

    :deep(.person) {
      &__photo {
        @apply xl:w-[195px] xl:h-[195px] xl:object-cover;
      }
    }
  }
}

.service-other {
  @apply grid grid-cols-1 lg:grid-cols-3 gap-20 lg:gap-30;
}
</style>
