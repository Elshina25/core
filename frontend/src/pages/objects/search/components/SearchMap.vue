<template>
  <div class="map">
    <div class="map-group">
      <YandexMap
        :id="OBJECTS_MAP_BLOCK_ID"
        class="h-full"
        :coords="activeCoords"
        :controls="controls"
        :zoom="zoom"
        :scroll-zoom="false"
        use-object-manager
        @map-was-initialized="init"
      >
        <YandexMapMarker
          v-for="(coord, idx) in coords"
          :key="idx"
          :coords="[coord.latitude, coord.longitude]"
          :marker-id="coord.id"
          :icon="MARKER_ICON"
        />
      </YandexMap>
      <div v-if="showObjectCard" class="card-block">
        <!-- TODO Vlad: исправить отображение карточки объекта при раскрытии карты на весь экран -->
        <div class="card-block__head">
          <span class="card-block__title">Найдено {{ objectsCount }}</span>
          <b-icon
            class="card-block__close"
            name="close"
            @click="showObjectCard = false"
          />
        </div>
        <b-card-building v-if="activeObjectCard" :building="activeObjectCard" />
      </div>
    </div>
  </div>
</template>

<script lang="ts" setup>
import BCardBuilding from '@/components/base/card/BCardBuilding.vue'
import BIcon from '@/components/base/icon/BIcon.vue'
import type { ObjectListResponse } from '@/api/object/list'
import type { ObjectPreviewDetailResponse } from '@/api/object/detail-preview'
import type { IMap } from '@/types/ui'
import { DEFAULT_COORDS, OBJECTS_MAP_BLOCK_ID } from '@/config/objects.config'
import { ROUTE } from '@/routes'
import { getDetailPreview } from '../requests'
import { pluralize } from '@/utils'
import { getSpace } from '@/utils/filter'
import isEqual from 'lodash-es/isEqual'
import { navigate } from 'vite-plugin-ssr/client/router'
import {
  defineAsyncComponent,
  defineProps,
  onMounted,
  shallowRef,
  ref,
  computed,
  watchEffect
} from 'vue'

const props = withDefaults(
  defineProps<{
    coords: {
      id: number
      latitude: number
      longitude: number
    }[]
    zoom?: number
  }>(),
  {
    zoom: 13
  }
)

const MARKER_ICON = {
  layout: 'default#image',
  imageHref: '/images/mapPointIcon.svg'
}

const activeCoords = ref(DEFAULT_COORDS)
const controls = ['fullscreenControl', 'geolocationControl', 'zoomControl']

watchEffect(() => {
  // Координаты 1-ого объекта в списке
  const objectCoords = [
    props.coords?.[0]?.latitude,
    props.coords?.[0]?.longitude
  ]
  // Если координаты 1-ого объекта не совпадают с текущими активными координатами, то обновляем их
  if (!props.coords.length || isEqual(activeCoords.value, objectCoords)) return
  activeCoords.value = objectCoords
})

// Компонент библиотеки
const YandexMap = shallowRef()
const YandexMapMarker = shallowRef()

// Подключаем библиотеку только для клиента
onMounted(() => {
  YandexMap.value = defineAsyncComponent(
    () =>
      // @ts-ignore
      import('vue-yandex-maps')
  )

  YandexMapMarker.value = defineAsyncComponent(
    () =>
      // @ts-ignore
      import('vue-yandex-maps/src/Marker')
  )
})

const init = (map: IMap) => {
  map.geoObjects.events.add('click', async (e) => {
    // id объекта
    const id = e.get('objectId')
    // Запрашиваем превью объекта
    const res: ObjectPreviewDetailResponse | null = await getDetailPreview(id)
    // Превью объекта не найдено
    if (!res) return
    // При размере экрана меньше 1440px, по нажатию на placemarker редиректим пользователя на детальную объекта
    if (window.screen.width < 1440) {
      const section = ROUTE.ESTATE.slug
      // Тип объекта
      const type = getSpace(res.propertyTypeId, 'id')?.type
      // Код объекта
      const code = res.code
      // Генерация ссылки на детальную объекта
      const url = [section, type, code].join('/')
      // Перенаправляем пользователя на детальную страницу объекта
      const navigationPromise = navigate(url)
      await navigationPromise
    } else {
      // Собираем превью
      activeObjectCard.value = {
        ...res,
        propertyType: res.propertyTypeId,
        pictures: res.picture ? [res.picture] : []
      }
      // Отображаем карточку объекта
      showObjectCard.value = true
    }
  })
}

const objectsCount = computed(() =>
  pluralize(props.coords.length, ['объявление', 'объявления', 'объявлений'])
)

const showObjectCard = ref<boolean>(false)
const activeObjectCard = ref<ObjectListResponse | null>(null)
</script>
<style lang="postcss">
.ymaps-2-1-79-fullscreen {
  .card-block {
    @apply z-[10001];
  }
}
</style>

<style lang="postcss" scoped>
.map {
  @apply relative h-[100vh] mobile:p-16 mt-32 2xl:mt-60;

  &-group {
    @apply h-full w-full bg-auxiliary-6/50;
  }
}
.card-block {
  @apply absolute right-60 top-40 bg-[#F3F3F3] px-40 pt-40 pb-54 w-fit max-h-[600px] overflow-auto;

  &__head {
    @apply flex items-center justify-between mb-22;
  }

  &__close {
    @apply cursor-pointer;
  }

  &__title {
    @apply t-button;
  }
}
</style>
