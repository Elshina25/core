<template>
  <div v-if="items.length" class="filter-quick">
    <span class="filter-quick__caption">Быстрый поиск: </span>
    <a
      v-for="(item, idx) in items"
      v-show="isShow(idx)"
      :key="idx"
      class="filter-quick__item link"
      :href="getQuickUrl(item)"
      >{{ item.title }}
    </a>

    <span v-if="showBtnAll" class="link link-button" @click="showAll = !showAll"
      >{{ showAll ? 'Свернуть список' : 'Раскрыть список' }}
    </span>
  </div>
</template>

<script lang="ts" setup>
import { computed } from 'vue'
import { storeToRefs } from 'pinia'
import { useFilterQuickStore } from '@/stores/filter/quick'
import { useFilterSpaceStore } from '@/stores/filter/spaces'
import { IFilterQuickData } from '@/types/filter'
import { useShowAll } from '@/composables/useShowAll'
import { ROUTE } from '@/routes'

const store = useFilterQuickStore()

const spaceStore = useFilterSpaceStore()
const { spaceType } = storeToRefs(spaceStore)

const items = computed<IFilterQuickData[]>(() => store?.[spaceType.value] ?? [])

const { showAll, showBtnAll, isShow } = useShowAll(items.value.length, 8)

/**
 * Ссылка на быстрый поиск
 * @param item
 * @return /estate/office/rent/class-a
 * TODO: написать единый резолвер
 */
const getQuickUrl = (item: IFilterQuickData) => {
  // TODO Vlad: временно переделал slug в queryParams
  return ROUTE.ESTATE.slug + `/${spaceType.value}/${item.offer}${item.slug}`
}
</script>

<style lang="postcss" scoped>
.filter-quick {
  @apply flex flex-wrap t-p3
    gap-y-8 gap-x-10
    md:gap-y-10 md:gap-x-20
    xl:gap-y-12 xl:gap-x-30;
}
</style>
