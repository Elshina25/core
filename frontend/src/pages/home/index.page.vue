<template>
  <home-first-screen :factoids="factoids" />
  <home-quote />
  <home-services :items="services" />
  <slider-what-new :items="whatsNew" />

  <client-only>
    <suspense>
      <slider-projects />
    </suspense>
  </client-only>

  <slider-research
    v-if="research.length"
    title="Аналитика и тренды рынка"
    :items="research"
  />

  <client-only>
    <suspense>
      <about-clients class="mt-80 md:mt-100 2xl:mt-[130px]" />
    </suspense>
  </client-only>

  <home-offers :items="objects" />

  <client-only>
    <suspense>
      <slider-team />
    </suspense>
  </client-only>

  <client-only>
    <suspense>
      <form-feedback />
    </suspense>
  </client-only>
</template>

<script lang="ts" setup>
import HomeFirstScreen from './components/HomeFirstScreen.vue'
import HomeQuote from './components/HomeQuote.vue'
import HomeServices from './components/HomeServices.vue'
import HomeOffers from './components/HomeOffers.vue'
import SliderResearch from '@/components/slider/SliderResearch.vue'
import SliderTeam from '@/components/slider/SliderTeam.vue'
import FormFeedback from '@/components/form/feedback/FormFeedback.vue'
import AboutClients from '../about/components/AboutClients.vue'
import SliderProjects from '@/components/slider/SliderProjects.vue'
import SliderWhatNew from '@/components/slider/SliderWhatNew.vue'
import ClientOnly from '@/components/base/ClientOnly.vue'
import { onMounted, onServerPrefetch } from 'vue'
import { useFactoidStore } from '@/stores/factoids'
import { storeToRefs } from 'pinia'
import { useServiceStore } from '@/stores/services'
import { useHomeResearchStore } from '@/stores/home/research'
import { useHomeObjectStore } from '@/stores/home/objects'
import { useHomeWhatsNewStore } from '@/stores/home/whatsNew'

const factoidStore = useFactoidStore()
const { factoids } = storeToRefs(factoidStore)

const serviceStore = useServiceStore()
const { services } = storeToRefs(serviceStore)

const whatsNewStore = useHomeWhatsNewStore()
const { whatsNew } = storeToRefs(whatsNewStore)

const researchStore = useHomeResearchStore()
const { research } = storeToRefs(researchStore)

const objectStore = useHomeObjectStore()
const { objects } = storeToRefs(objectStore)

const fetchData = async () => {
  await factoidStore.fetchList()
  await serviceStore.fetchList()
  await whatsNewStore.fetchList()
  await researchStore.fetchList()
  await objectStore.fetchList()
}

onServerPrefetch(fetchData)
onMounted(fetchData)
</script>

<script lang="ts">
export { onBeforeRender } from './onBeforeRender'
</script>
