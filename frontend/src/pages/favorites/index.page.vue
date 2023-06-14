<template>
  <div class="container">
    <b-breadcrumb class="md:mb-30 2xl:mb-40">
      <b-breadcrumb-item :to="$ROUTE.HOME.slug"
        >{{ $ROUTE.HOME.title }}
      </b-breadcrumb-item>
      <b-breadcrumb-item>{{ $ROUTE.FAVORITES.title }}</b-breadcrumb-item>
    </b-breadcrumb>

    <h1 class="title mt-30 2xl:mt-60">Избранное</h1>
    <div v-if="favoritesWithData.length" class="share-group">
      <b-share :url="baseUrl" :text="shareText">
        <template #label>
          <span class="mobile:!hidden link link-button t-p3">
            Поделиться подборкой
          </span>
          <span class="md:!hidden link link-button t-p2"> Поделиться </span>
        </template>
      </b-share>
      <b-favorite-clear />
    </div>

    <div class="relative">
      <b-loader />
      <object-list :list="favoritesWithData">
        <template #no-result>
          <list-no-result
            v-if="!favorites.getList.length"
            title="Список избранного пуст"
            description="Вы можете добавить объекты в избранное на странице "
            link-label="объектов недвижимости"
            link="/estate/office"
            class="mt-30 md:mt-40 2xl:mt-60"
          />
          <list-no-result
            v-else
            title="Загружаем список избранного"
            description="Вы можете добавить объекты в избранное на странице "
            link-label="объектов недвижимости"
            link="/estate/office"
            class="mt-30 md:mt-40 2xl:mt-60"
          />
        </template>
      </object-list>
    </div>
  </div>
</template>

<script lang="ts" setup>
import BBreadcrumb from '@/components/base/breadcrumb/BBreadcrumb.vue'
import BBreadcrumbItem from '@/components/base/breadcrumb/BBreadcrumbItem.vue'
import BShare from '@/components/base/share/BShare.vue'
import BFavoriteClear from '@/components/base/favorite/BFavoriteClear.vue'
import { onMounted, Ref, ref, watchEffect, computed } from 'vue'
import { ObjectListResponse } from '@/api/object/list'
import ListNoResult from '@/components/table/ListNoResult.vue'
import ObjectList from '@/pages/objects/list/components/ObjectList.vue'
import { getList } from './requests'
import { useLoader } from '@/composables/useLoader'
import BLoader from '@/components/base/loader/BLoader.vue'
import { useFavoritesStore } from '@/stores/favorites'
import { ROUTE } from '@/routes'
import { getSpace } from '@/utils/filter'
import { NEW_LINE_SEPARATOR } from '@/config/share.config'

const favoritesWithData: Ref<ObjectListResponse[]> = ref([])

const loading = useLoader()
const favorites = useFavoritesStore()

onMounted(async () => {
  if (!favorites.getList.length) return

  loading.start()
  favoritesWithData.value = await getList({
    'id[]': favorites.getList
  })
  loading.stop()
})

watchEffect(async () => {
  if (favorites.getList.length) {
    favoritesWithData.value = favoritesWithData.value.filter((el) =>
      favorites.isActive(el.id)
    )
  } else {
    favoritesWithData.value = []
  }
})

const baseUrl = import.meta.env.VITE_BASE_URL

const shareText = computed(() => {
  return favoritesWithData.value
    .map((el) => {
      const section = ROUTE.ESTATE.slug
      const type = getSpace(el.propertyType, 'id')?.type
      const code = el.code

      const url = `${baseUrl}${[section, type, code].join('/')}`
      // Добавляем в конце каждой строки NEW_LINE_SEPARATOR для переноса на новую строку (см. BShare.vue -> activeLinks)
      return `${el.propertyNameRus}: ${url} ${NEW_LINE_SEPARATOR}`
    })
    .join('')
})
</script>
<script lang="ts">
export { onBeforeRender } from './onBeforeRender'
</script>

<style src="@/assets/styles/components/share-group.css" scoped></style>
