<template>
  <div>
    <h2 class="mb-20 2xl:mb-60">Местоположение</h2>

    <b-map v-bind="map" class="mb-20 2xl:mb-60" />

    <div v-if="object.metro" class="flex flex-col items-end">
      <map-station-item
        class="mb-6 md:mb-8 2xl:mb-20 last:mb-0"
        :station="object.metro"
      />
    </div>
  </div>
</template>

<script lang="ts" setup>
import { IMap } from '@/types'
import { injectStrict } from '@/utils'
import { KEY_OBJECT } from '../injectionKeys'
import BMap from '@/components/base/map/BMap.vue'
import MapStationItem from '@/components/station/MapStationItem.vue'
import { computed } from 'vue'

const object = injectStrict(KEY_OBJECT)

const map = computed<IMap>(() => {
  return {
    coords: [
      {
        latitude: object.value.xCoordinate,
        longitude: object.value.yCoordinate
      }
    ]
  }
})
</script>
