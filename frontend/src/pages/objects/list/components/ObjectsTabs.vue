<template>
  <b-tabs v-if="tabs.length" :active-tab="activeTab">
    <b-tab v-for="tab in tabs" :key="tab.id" :title="tab.name">
      <div class="container">
        <objects-tab :tab="tab" />
      </div>
    </b-tab>
  </b-tabs>
</template>

<script lang="ts" setup>
import BTabs from '@/components/base/tabs/BTabs.vue'
import BTab from '@/components/base/tabs/BTab.vue'
import ObjectsTab from './ObjectsTab.vue'
import type { IObjectTab, IFastSearch } from '@/types'
import { FILTER_CITY_DEFAULT_ID } from '@/config/filter/filter.config'

import { useFilterCityStore } from '@/stores/filter/cities'
import { useFilterMetroStore } from '@/stores/filter/metros'
import { useFilterDistrictStore } from '@/stores/filter/districts'
import { useFilterClassStore } from '@/stores/filter/classes'
import { useFilterCountieStore } from '@/stores/filter/counties'
import { useFilterSpaceStore } from '@/stores/filter/spaces'

import { ROUTE } from '@/routes'
import { getSpace } from '@/utils/filter'
import { usePageContext } from '~/renderer/utils'
import { ref, computed, watch } from 'vue'

const store = {
  city: useFilterCityStore(),
  metro: useFilterMetroStore(),
  district: useFilterDistrictStore(),
  class: useFilterClassStore(),
  counties: useFilterCountieStore(),
  space: useFilterSpaceStore()
}

const pageContext = usePageContext()

const baseUrl = computed(() => {
  const offer = pageContext.routeParams.offer || 'rent'
  return `${ROUTE.ESTATE.slug}/${pageContext.routeParams.space}/${offer}`
})

const tabs = computed<IObjectTab[]>(() =>
  [
    {
      id: 1,
      name: 'Округ',
      title: 'Предложения по округам',
      values:
        store.city.selected === FILTER_CITY_DEFAULT_ID &&
        !['1', '4'].includes(store.space.selected)
          ? store.counties.options.map(
              ({ id, nameRu, slug }): IFastSearch => ({
                id,
                name: nameRu,
                path: `${baseUrl.value}/${slug}`
              })
            )
          : []
    },
    {
      id: 2,
      name: 'Район',
      title: 'Предложения по районам',
      values:
        // @see: /src/config/filter/filter-form.config.ts [145-158]
        store.city.selected === FILTER_CITY_DEFAULT_ID &&
        store.space.selected !== '1'
          ? store.district.options.map(
              ({ areaId, areaSlug, areaNameRu }): IFastSearch => ({
                id: areaId,
                name: areaNameRu,
                path: `${baseUrl.value}/district-${areaSlug}`
              })
            )
          : []
    },
    {
      id: 3,
      name: 'Метро',
      title: 'Предложения по метро',
      values:
        // @see: /src/config/filter/filter-form.config.ts [145-158]
        store.city.selected === FILTER_CITY_DEFAULT_ID &&
        store.space.selected !== '1'
          ? store.metro.options.map(
              (el): IFastSearch => ({
                ...el,
                path: `${baseUrl.value}/metro-${el.slug}`
              })
            )
          : []
    },
    {
      id: 4,
      name: 'Класс здания',
      title: 'Предложения по классу здания',
      values: store.class.options.map(
        (el): IFastSearch => ({
          ...el,
          path: `${baseUrl.value}/class-${el.type}`
        })
      )
    },
    {
      id: 5,
      name: 'Тип помещения',
      title: 'Предложения по типу помещения',
      values: store.space.convertedOptions.map((el): IFastSearch => {
        const type = getSpace(el.id, 'id')?.type
        const offer = pageContext.routeParams.offer || 'rent'
        return {
          ...el,
          path: type ? `${ROUTE.ESTATE.slug}/${type}/${offer}` : ''
        }
      })
    }
  ].filter((el) => el.values.length)
)

const activeTab = ref('')

watch(tabs, () => {
  activeTab.value = tabs.value[0].name
})
</script>
