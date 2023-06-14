<template>
  <div class="relative">
    <div
      v-click-outside="clearResult"
      class="filter-element filter-element--border"
    >
      <input
        v-model="searchModel"
        placeholder="Адрес или название"
        class="filter-element__input"
        @focus="showResult = true"
        @input="requestSearch"
        @keydown.enter="handleEnterClick"
      />
      <slot />
    </div>

    <div
      v-show="showResult && items.length"
      class="search-result filter-element filter-element--border"
    >
      <filter-field-search-item
        v-for="item in items"
        :key="item.id"
        :label="item.name"
        :icon="item.icon"
        :collection="item.collection"
        :prefix="item.prefix"
        @click.stop="pickSearchItem(item)"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
import type { IAddressSearchItem } from '@/api/address/search'
import { Ref, ref } from 'vue'
import { vClickOutside } from '@/composables/useClickOutside'
import FilterFieldSearchItem from './FilterFieldSearchItem.vue'
import api from '@/api'
import debounce from 'lodash-es/debounce'
import { useFilterSearchStore } from '@/stores/filter/search'
import { IObjectSearch, IObjectSearchResponse } from '@/types/objects'

defineProps<{
  placeholder?: string
}>()

const MIN_SEARCH_LENGTH = 3

const searchStore = useFilterSearchStore()

const searchModel = ref(searchStore.name || searchStore.address)

const items: Ref<IObjectSearch[]> = ref([])

const showResult = ref(false)

const fetchOptions = async () => {
  const { data } = await api.object.search(searchModel.value)
  const { data: addressData } = await api.address.search({
    address: searchModel.value
  })

  // Список объектов
  const objectsList = data.items.map((value: IObjectSearchResponse) => ({
    ...value,
    collection: 'ui',
    icon: 'location-fill'
  }))

  // Список адресов
  const addressList = addressData.items.map((el: IAddressSearchItem) => ({
    name: el.address,
    code: '',
    collection: 'ui',
    icon: 'location'
  }))

  //Добавление иконок для отображения в дропдауне
  items.value = [...addressList, ...objectsList]

  //Добавление дополнительной опции - для поиска по адресу
  items.value.unshift({
    id: 1,
    name: searchModel.value,
    prefix: 'По названию: ',
    code: '',
    collection: 'ui',
    icon: 'filter-search'
  })

  items.value.unshift({
    prefix: 'По адресу: ',
    name: searchModel.value,
    code: '',
    collection: 'ui',
    icon: 'filter-search'
  })
}

//Метод запроса поисковой выдачи
const requestSearch = debounce(async () => {
  //очистка полей
  items.value = []
  searchStore.address = searchStore.name = ''

  if (searchModel.value.length >= MIN_SEARCH_LENGTH) {
    await fetchOptions()
    showResult.value = true
  }
}, 500)

const pickSearchItem = async (item: IObjectSearch) => {
  searchModel.value = item.name

  //Если есть айди - то это результат с сервера, если нет - то поиск по адресу
  //Должно быть заполнено только одно поле, при изменении одного - очистка другого
  if (item.id) {
    searchStore.name = item.name
    searchStore.address = ''
  } else {
    searchStore.address = item.name
    searchStore.name = ''
  }
  showResult.value = false
  await fetchOptions()
}

const clearResult = () => {
  if (showResult.value) {
    searchModel.value = ''
    items.value = []
    showResult.value = false
  }
}

const handleEnterClick = () => {
  if (!searchStore.name && searchModel.value.length >= MIN_SEARCH_LENGTH) {
    searchStore.address = searchModel.value
  }
  showResult.value = false
}

if (searchModel.value) {
  await fetchOptions()
}
</script>

<style src="./search.css" scoped></style>
