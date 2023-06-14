<template>
  <div class="container">
    <b-breadcrumb class="md:mb-30 2xl:mb-60">
      <b-breadcrumb-item :to="$ROUTE.HOME.slug">{{
        $ROUTE.HOME.title
      }}</b-breadcrumb-item>
      <b-breadcrumb-item :to="`${$ROUTE.ESTATE.slug}/${spaceType}`">{{
        $ROUTE.ESTATE.title
      }}</b-breadcrumb-item>
      <b-breadcrumb-item>{{ object.title }}</b-breadcrumb-item>
    </b-breadcrumb>

    <object-filters />
    <object-head />

    <div class="object-detail">
      <b-gallery :items="object.images" />
      <object-details />
    </div>

    <div class="object-body">
      <object-info class="order-1 lg:w-[calc(100%-480px)] mb-40 lg:mb-100" />
      <object-specialist-list
        class="order-4 lg:order-2"
        :persons="object.contacts"
      />
      <object-table id="offers" class="order-3 mobile:mb-60" />
    </div>

    <form-object-consult
      title="Получить консультацию по объектам"
      :crm-id="object.crmId"
      class="mb-60 lg:mb-100"
    />
    <object-map class="mb-60 lg:mb-100" />

    <object-characteristic
      v-if="false"
      class="mb-40 md:mb-60 lg:mb-100"
      title="Общие характеристики объекта Искра-Парк"
      :labels="[]"
    />

    <object-characteristic
      v-if="false"
      class="mb-40 md:mb-60 lg:mb-100"
      title="Инфраструктура"
      :labels="[]"
    />

    <object-characteristic
      v-if="false"
      class="mb-80 lg:mb-150"
      title="Технические характеристики"
      :labels="[]"
    />

    <object-order-review :id="OBJECT_REQUEST_FORM_ID" />
  </div>

  <slider-objects :items="others" />
</template>

<script setup lang="ts">
import { computed, provide } from 'vue'
import { KEY_OBJECT, KEY_SHARE } from './injectionKeys'
import { PageProps } from '@/pages/objects/detail/types'
import BGallery from '@/components/base/gallery/BGallery.vue'
import BBreadcrumb from '@/components/base/breadcrumb/BBreadcrumb.vue'
import BBreadcrumbItem from '@/components/base/breadcrumb/BBreadcrumbItem.vue'
import ObjectHead from './components/ObjectHead.vue'
import ObjectDetails from './components/ObjectDetails.vue'
import ObjectFilters from './components/ObjectFilters.vue'
import ObjectInfo from './components/ObjectInfo.vue'
import ObjectTable from './components/ObjectTable.vue'
import ObjectMap from './components/ObjectMap.vue'
import ObjectSpecialistList from './components/ObjectSpecialistList.vue'
import ObjectOrderReview from './components/ObjectOrderReview.vue'
import FormObjectConsult from '@/components/form/object/FormObjectConsult.vue'
import SliderObjects from '@/components/slider/SliderObjects.vue'
import ObjectCharacteristic from '@/pages/objects/detail/components/ObjectCharacteristic.vue'
import { getSpace } from '@/utils/filter'
import { OBJECT_REQUEST_FORM_ID } from '@/config/objects.config'
import { SOCIALS_SHARE } from '@/config/share.config'

const props = defineProps<PageProps>()

const spaceType = computed(
  () => getSpace(props.object.propertyType.id, 'id')?.type
)

const object = computed(() => props.object)

provide(KEY_OBJECT, object)
provide(KEY_SHARE, SOCIALS_SHARE)
</script>

<script lang="ts">
export { onBeforeRender } from './onBeforeRender'
</script>

<style lang="postcss" scoped>
.object {
  &-detail {
    @apply gap-16 md:gap-20 lg:gap-30 flex flex-col lg:flex-row mb-40 lg:mb-60;
  }

  &-body {
    @apply mb-60 lg:mb-100 flex flex-col lg:flex-row flex-wrap lg:gap-x-60;
  }
}
</style>
