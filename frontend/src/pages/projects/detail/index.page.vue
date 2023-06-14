<template>
  <div class="container">
    <b-breadcrumb class="mb-30 md:mb-50 2xl:mb-70">
      <b-breadcrumb-item :to="$ROUTE.HOME.slug">
        {{ $ROUTE.HOME.title }}
      </b-breadcrumb-item>
      <b-breadcrumb-item :to="$ROUTE.PROJECTS.slug">
        {{ $ROUTE.PROJECTS.title }}
      </b-breadcrumb-item>
      <b-breadcrumb-item>{{ project.name }}</b-breadcrumb-item>
    </b-breadcrumb>

    <project-head
      :tags="tags"
      :date="project.date"
      :share="SOCIALS_SHARE"
      :photo="getFullUrl(project.image)"
      :title="project.name"
    />

    <div class="project-info">
      <div class="mb-60 xl:mb-80 mobile:order-2 w-full">
        <h2 class="mb-20 xl:mb-40">Описание проекта</h2>
        <b-article :content="project.detailText" />
      </div>
      <project-authors
        v-if="project.persons.length"
        class="xl:max-w-[390px] mobile:order-1"
        :authors="project.persons"
      />
    </div>
    <form-object-consult
      :title="formLabels.title"
      :subtitle="formLabels.description"
      class="mb-[70px] md:mb-60 xl:mb-100"
    />
    <project-quote
      v-if="false"
      author="Елена Жук"
      job-title="Генеральный директор Aktion Development"
      quote="«Наша компания не обладала опытом работы с крупными госкорпорациями и особенно ценным было то, что CORE.XP в своем лице смогло оказать самую всестороннюю поддержку. В разные периоды сделки коллеги оказывались для нас и профессиональными партнерами, и проникновенными психологами, и неустанными мотиваторами, да и просто надежными друзьями. Эта синергия позволила преодолеть долгий, но увлекательный путь и дойти до результата»."
    />
    <div v-if="project.linkedServices.length" class="mb-60 xl:mb-150">
      <h2 class="mb-40">Оказанные услуги</h2>
      <project-service
        v-for="service in project.linkedServices"
        :key="service.code"
        :item="service"
        class="mb-20 last:mb-0"
      />
    </div>
  </div>
  <project-slider-gallery
    v-if="project.photos.length"
    :items="project.photos"
  />
  <slider-project v-if="projectSlider.length" :items="projectSlider" />
  <slider-services :items="serviceSlider" class="mt-80 md:mt-100 xl:mt-150" />
  <div class="container">
    <form-subscribe-project
      class="mt-80 md:mt-100 xl:mt-150"
      :crm-id="project.id"
    />
  </div>
</template>

<script lang="ts" setup>
import BBreadcrumb from '@/components/base/breadcrumb/BBreadcrumb.vue'
import BBreadcrumbItem from '@/components/base/breadcrumb/BBreadcrumbItem.vue'
import { computed, ComputedRef } from 'vue'
import { PageProps } from './types'
import { IFormLabels, ITag } from '@/types'
import { getFullUrl } from '@/utils'
import { SOCIALS_SHARE } from '@/config/share.config'
import SliderServices from '@/components/slider/SliderServices.vue'
import ProjectHead from '@/pages/projects/detail/components/ProjectHead.vue'
import ProjectAuthors from '@/pages/projects/detail/components/ProjectAuthors.vue'
import FormObjectConsult from '@/components/form/object/FormObjectConsult.vue'
import ProjectSliderGallery from '@/pages/projects/detail/components/ProjectSliderGallery.vue'
import ProjectQuote from '@/pages/projects/detail/components/ProjectQuote.vue'
import ProjectService from '@/pages/projects/detail/components/ProjectService.vue'
import FormSubscribeProject from '@/components/form/project/FormSubscribeProject.vue'
import SliderProject from '@/components/slider/SliderProject.vue'
import BArticle from '@/components/base/article/BArticle.vue'

const props = defineProps<PageProps>()

const tags = computed<string[]>(() =>
  [props.project.section, props.project.type]
    .map((el: ITag): string => el.name)
    .filter((el) => !!el)
)

const formLabels: ComputedRef<IFormLabels> = computed(() => {
  return {
    title: props.project.form.title || 'Получить консультацию',
    description:
      props.project.form.description ||
      'Если вам нужна консультация, оставьте ваши контакты, и мы свяжемся с вами в ближайшее время'
  }
})
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

.project-info {
  @apply mt-30 md:mt-40 xl:mt-80 flex mobile:flex-col gap-40 md:gap-20 xl:gap-30;
}
</style>
