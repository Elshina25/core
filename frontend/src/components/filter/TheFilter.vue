<template>
  <form class="filter" @submit.prevent="submit">
    <div class="filters-group">
      <the-filter-tabs />
      <the-filter-main />
      <the-filter-collapse />
    </div>

    <slot />
    <the-filter-quick v-if="showQuick" />
  </form>
</template>

<script lang="ts" setup>
import { navigate } from 'vite-plugin-ssr/client/router'
import { useFilterLoaderStore } from '@/stores/filter/loader'
import { useFilterStoreGenerator } from '@/composables/filter/useFilterLinkGenerator'
import TheFilterMain from '@/components/filter/TheFilterMain.vue'
import TheFilterCollapse from '@/components/filter/TheFilterCollapse.vue'
import TheFilterQuick from '@/components/filter/TheFilterQuick.vue'
import TheFilterTabs from '@/components/filter/TheFilterTabs.vue'

withDefaults(
  defineProps<{
    showQuick?: boolean
  }>(),
  {
    showQuick: true
  }
)

const loader = useFilterLoaderStore()
const { showOfferListLink, showOnMapLink } = useFilterStoreGenerator()

/**
 * Запустить фильтр по нажатию Enter
 */
const submit = () => {
  if (loader.isMap) {
    navigate(showOnMapLink.value)
  } else {
    navigate(showOfferListLink.value)
  }
}
</script>

<style lang="postcss" scoped>
.filters-group {
  --mb: calc(var(--filter-collapse-h) + 16px);
  --mb-md: calc(var(--filter-collapse-h-md) + 16px);
  --mb-xl: calc(var(--filter-collapse-h-xl) + 20px);

  @apply relative mb-[var(--mb)] md:mb-[var(--mb-md)] xl:mb-[var(--mb-xl)];
}
</style>
