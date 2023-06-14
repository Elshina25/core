<template>
  <div class="container">
    <b-breadcrumb class="mb-30 md:mb-50 2xl:mb-70">
      <b-breadcrumb-item :to="$ROUTE.HOME.slug">{{
        $ROUTE.HOME.title
      }}</b-breadcrumb-item>
      <b-breadcrumb-item :to="$ROUTE.RESEARCHES.slug">{{
        $ROUTE.RESEARCHES.title
      }}</b-breadcrumb-item>
      <b-breadcrumb-item>{{ research.name }}</b-breadcrumb-item>
    </b-breadcrumb>

    <research-head
      :tags="tags"
      :share="SOCIALS_SHARE"
      :photo="getFullUrl(research.image)"
      :title="research.name"
      :date="research.date"
    />

    <div class="mt-24 md:mt-30 2xl:mt-80">
      <research-authors
        v-if="research.authors.length"
        class="xl:float-right xl:max-w-[390px]"
        :authors="research.authors"
      />

      <research-factoids :items="research.facts" />

      <div class="mt-42 md:mt-56 xl:mt-76">
        <template v-for="(text, idx) in separatedText" :key="idx">
          <b-article :content="text"></b-article>
          <template v-if="isOrderResearchFormActive(idx)">
            <form-order-research-stub
              scroll-to="#request-research-mobile"
              class="my-40 md:my-60 xl:my-80"
            />
            <form-order-research
              show-only-on="desktop"
              class="my-40 md:my-80 xl:my-84"
            />
          </template>
        </template>
      </div>
    </div>
    <form-request-research
      v-if="research.short"
      :research="research"
      title="Запросить полное исследование"
    />
    <form-order-research
      v-if="showOrderResearchForm"
      id="request-research-mobile"
      show-only-on="mobile"
      class="my-80"
    />

    <template v-if="!research.short">
      <h2 class="t-h1 mt-80 md:mt-100 2xl:mt-150">Обратная связь</h2>
      <research-form-feedback />
    </template>
  </div>

  <template v-if="researchList.length">
    <slider-research :items="researchList" title="Читайте также по теме" />
  </template>
</template>

<script lang="ts" setup>
import BArticle from '@/components/base/article/BArticle.vue'
import BBreadcrumb from '@/components/base/breadcrumb/BBreadcrumb.vue'
import BBreadcrumbItem from '@/components/base/breadcrumb/BBreadcrumbItem.vue'
import ResearchHead from './components/ResearchHead.vue'
import ResearchFactoids from './components/ResearchFactoids.vue'
import ResearchAuthors from './components/ResearchAuthors.vue'
import ResearchFormFeedback from './components/ResearchFormFeedback.vue'
import SliderResearch from '@/components/slider/SliderResearch.vue'

import { computed } from 'vue'
import { PageProps } from './types'
import { ITag } from '@/types'
import { getFullUrl } from '@/utils'
import { SOCIALS_SHARE } from '@/config/share.config'
import FormOrderResearch from '@/components/form/research/FormOrderResearch.vue'
import FormOrderResearchStub from '@/components/form/research/FormOrderResearchStub.vue'
import FormRequestResearch from '@/components/form/research/FormRequestResearch.vue'

const props = defineProps<PageProps>()

// Флаг отвечающий за отображение формы заказа исследования в тексте
const showOrderResearchForm = computed<boolean>(
  () =>
    props.research.detailText.includes('#FORM_ORDER_RESEARCH#') &&
    // Свойство отвечает за отображение полной, либо короткой версии исследования
    !props.research.short
)

// Формируем массив для отображения текста и формы заказа исследования
const separatedText = computed<string[]>(() =>
  props.research.detailText.split('#FORM_ORDER_RESEARCH#')
)

/**
 * Отображение формы заказа исследования в тексте
 * @param {number} idx
 * @returns {boolean}
 * @description метод возвращает флаг для отображения формы.
 * Разделителем текста является ключевое слово #FORM_ORDER_RESEARCH#, которое отвечает за отображение формы,
 * следовательно необходимо отобразить форму после каждого элемента массива, кроме последнего
 */
const isOrderResearchFormActive = (idx: number): boolean => {
  return showOrderResearchForm.value && idx !== separatedText.value.length - 1
}

const tags = computed<string[]>(() =>
  [props.research.section, props.research.type].map(
    (el: ITag): string => el.name
  )
)
</script>

<script lang="ts">
export { onBeforeRender } from './onBeforeRender'
</script>

<style lang="postcss" scoped>
.text-shadow {
  background: linear-gradient(
    180deg,
    #505050 29.31%,
    rgba(80, 80, 80, 0) 90.4%
  );
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}
</style>
