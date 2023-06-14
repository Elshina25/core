<template>
  <about-first-screen />
  <about-main-info />
  <about-factoids :factoids="factoids" />

  <client-only>
    <suspense>
      <about-clients class="mb-80 md:mb-[70px] 2xl:mb-[180px]" />
    </suspense>
  </client-only>

  <client-only>
    <suspense>
      <slider-team class="!mt-0" />
    </suspense>
  </client-only>

  <about-awards :awards="awards" />
  <slider-about-news :items="news.items" class="mb-60 lg:mb-80 2xl:mb-150" />

  <client-only>
    <suspense>
      <form-feedback />
    </suspense>
  </client-only>
</template>

<script setup lang="ts">
import AboutFirstScreen from './components/AboutFirstScreen.vue'
import AboutMainInfo from './components/AboutMainInfo.vue'
import AboutFactoids from './components/AboutFactoids.vue'
import AboutClients from './components/AboutClients.vue'
import SliderTeam from '@/components/slider/SliderTeam.vue'
import AboutAwards from './components/AboutAwards.vue'
import FormFeedback from '@/components/form/feedback/FormFeedback.vue'
import ClientOnly from '@/components/base/ClientOnly.vue'
import { PageProps } from './types'
import SliderAboutNews from '@/components/slider/SliderAboutNews.vue'
import { useFactoidStore } from '@/stores/factoids'
import { storeToRefs } from 'pinia'
import { onMounted, onServerPrefetch } from 'vue'

defineProps<PageProps>()

const factoidStore = useFactoidStore()
const { factoids } = storeToRefs(factoidStore)

const fetchData = async () => {
  await factoidStore.fetchList()
}

onServerPrefetch(fetchData)
onMounted(fetchData)
</script>

<script lang="ts">
export { onBeforeRender } from './onBeforeRender'
</script>

<style lang="postcss" scoped></style>
