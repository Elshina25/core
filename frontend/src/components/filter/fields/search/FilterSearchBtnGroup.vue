<template>
  <div class="btn-group">
    <b-filter-button
      v-for="(tab, idx) in tabs"
      :key="idx"
      variant="link"
      type="div"
      :class="{ 'ml-16': idx !== 0 }"
      class="btn-group__btn"
      @click="openModal(tab)"
    >
      <template #before>
        <span v-if="tab.count" class="btn-group__caption">{{ tab.count }}</span>
      </template>
      {{ tab.label }}
    </b-filter-button>
    <b-filter-modal v-model="modal.status" container fill>
      <filter-modal-area
        v-model="activeTab.selected"
        :tabs="tabs"
        :active-tab="activeTab"
        @on-click-tab="changeActiveTab"
      />
    </b-filter-modal>
  </div>
</template>

<script lang="ts" setup>
import BFilterButton from '../../base/button/BFilterButton.vue'
import BFilterModal from '../../base/modal/BFilterModal.vue'
import FilterModalArea from '../../modal/FilterModalArea.vue'

import { useFilterMetroStore } from '@/stores/filter/metros'
import { useFilterDistrictStore } from '@/stores/filter/districts'

import { useModal } from '@/composables/useModal'
import { IFilterArea } from '@/types/ui'
import { FILTER_CITY_DEFAULT_ID } from '@/config/filter/filter.config'
import { reactive, ref, watch } from 'vue'

const metro = useFilterMetroStore()
const district = useFilterDistrictStore()

const tabs = reactive<IFilterArea[]>([
  {
    id: 'metro',
    label: 'Метро',
    get selected() {
      return metro.selected
    },
    set selected(val) {
      metro.selected = val
    },
    get values() {
      return metro.options
    },
    get count() {
      return this.selected?.length
    }
  },
  {
    id: 'area',
    label: 'Район',
    get selected() {
      return district.selected
    },
    set selected(val) {
      district.selected = val
    },
    get values() {
      return district.convertedOptions
    },
    get count() {
      return this.selected?.length
    }
  }
])

const modal = useModal()

const activeTab = ref<IFilterArea>(tabs[0])

const changeActiveTab = (tab: IFilterArea) => {
  activeTab.value = tab
}

let pageYOffset = 0

watch(
  () => modal.status,
  () => {
    if (window.innerWidth > 768) return
    if (modal.status) {
      pageYOffset = window.pageYOffset
      window.scrollTo({ top: 0 })
    } else {
      window.scrollTo({ top: pageYOffset })
    }
  }
)

const openModal = (val: IFilterArea) => {
  changeActiveTab(val)
  modal.open()
}

await metro.fetchOptions()
await district.fetchOptions({ cities: [FILTER_CITY_DEFAULT_ID] })
</script>

<style src="./search-btn-group.css" scoped></style>
