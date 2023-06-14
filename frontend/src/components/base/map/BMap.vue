<template>
  <YandexMap
    id="map"
    class="h-[360px] md:h-[418px] 2xl:h-[750px]"
    :coords="[coords[0].latitude, coords[0].longitude]"
    :controls="['fullscreenControl', 'geolocationControl', 'zoomControl']"
    :zoom="zoom"
  >
    <YandexMapMarker
      v-for="(coord, idx) in coords"
      :key="idx"
      :coords="[coord.latitude, coord.longitude]"
      :marker-id="idx"
      :icon="MARKER_ICON"
    />
  </YandexMap>
</template>

<script lang="ts" setup>
import { defineAsyncComponent, defineProps, onMounted, shallowRef } from 'vue'
import { IMap } from '@/types'

withDefaults(defineProps<IMap>(), {
  zoom: 15
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

const MARKER_ICON = {
  layout: 'default#image',
  imageHref: '/images/mapPointIcon.svg',
  imageSize: [80, 80]
}
</script>
