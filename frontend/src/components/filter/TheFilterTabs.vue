<template>
  <div class="offer-tabs">
    <button
      v-for="(tab, idx) in FILTER_OFFER_TABS"
      :key="idx"
      class="offer-tab"
      :class="{ active: store.selected === tab.type }"
      @click="store.change(tab.type)"
    >
      {{ tab.name }}
    </button>
  </div>
</template>

<script setup lang="ts">
import { FILTER_OFFER_TABS } from '@/config/filter/filter.config'
import { useFilterOfferStore } from '@/stores/filter/offers'
import { usePageContext } from '~/renderer/utils'

const pageContext = usePageContext()
const store = useFilterOfferStore()
store.setDefaultParams({ offer: pageContext.routeParams.offer })
</script>

<style lang="postcss" scoped>
.offer-tabs {
  .offer-tab {
    @apply t-p2 p-16 md:py-18 md:px-16 xl:px-20 xl:py-18 xl:pt-20 rounded-t transition-all;

    &:not(&.active):hover {
      @apply bg-white/50;
    }

    &.active {
      @apply bg-white;
    }
  }
}
</style>
