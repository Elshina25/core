<template>
  <!--TODO: позже доработать, чтобы принимало дополнительные параметры-->
  <div class="flex flex-wrap gap-6 2xl:gap-10">
    <b-icon
      name="station"
      class="station-icon"
      :style="`--station-color: #${station.color}`"
    />
    <a :href="URL" class="t-p3 whitespace-nowrap link">{{ station.name }}</a>
    <div class="t-p3 whitespace-nowrap text-black">
      ~{{ station.distance }} мин
    </div>
  </div>
</template>

<script lang="ts" setup>
import { computed } from 'vue'
import { ROUTE } from '@/routes'
import { IStation } from '@/types/objects'
import { usePageContext } from '~/renderer/utils'
import BIcon from '@/components/base/icon/BIcon.vue'

const props = defineProps<{
  station: IStation
}>()

const pageContext = usePageContext()

const URL = computed(() => {
  const section = ROUTE.ESTATE.slug
  const space = pageContext.routeParams.space ?? 'office'
  const offer = pageContext.routeParams.offer ?? 'rent'

  return [section, space, offer, `metro-${props.station.code}`].join('/')
})
</script>
<style src="./station.css" scoped></style>
