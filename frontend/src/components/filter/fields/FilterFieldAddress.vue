<template>
  <div class="relative">
    <div class="filter-element filter-element--border">
      <input
        v-model="address.selected"
        placeholder="Адрес или название"
        class="filter-element__input"
      />

      <div v-if="areaAndMetroActiveFor()" class="filter-element__btn-group">
        <b-filter-button
          v-for="(tab, idx) in tabs"
          :key="idx"
          variant="link"
          type="div"
          :class="{ 'ml-16': idx !== 0 }"
          class="filter-element__btn"
          @click="openModal(tab)"
        >
          <template #before>
            <span v-if="tab.count" class="filter-element__caption">{{
              tab.count
            }}</span>
          </template>
          {{ tab.label }}
        </b-filter-button>
      </div>
    </div>

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

<script setup lang="ts">
import BFilterButton from '../base/button/BFilterButton.vue'
import BFilterModal from '../base/modal/BFilterModal.vue'
import FilterModalArea from '../modal/FilterModalArea.vue'
import { IFilterArea } from '@/types/ui'
import { useModal } from '@/composables/useModal'
import { FILTER_CITY_DEFAULT_ID } from '@/config/filter/filter.config'
import { useFilterMetroStore } from '@/stores/filter/metros'
import { useFilterDistrictStore } from '@/stores/filter/districts'
import { useFilterAddressStore } from '@/stores/filter/address'
import { reactive, ref, watch } from 'vue'

defineProps<{
  areaAndMetroActiveFor: () => boolean
}>()

const metro = useFilterMetroStore()
const district = useFilterDistrictStore()
const address = useFilterAddressStore()

metro.setDefaultParams()
district.setDefaultParams()
address.setDefaultParams()

let pageYOffset = 0
const modal = useModal()
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

const activeTab = ref<IFilterArea>(tabs[0])

await metro.fetchOptions()
await district.fetchOptions({ cities: [FILTER_CITY_DEFAULT_ID] })

const changeActiveTab = (tab: IFilterArea) => {
  activeTab.value = tab
}

const openModal = (val: IFilterArea) => {
  changeActiveTab(val)
  modal.open()
}

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
</script>

<style src="./search/search.css" scoped></style>
