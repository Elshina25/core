<template>
  <div class="filters">
    <a
      :href="`${$ROUTE.ESTATE.slug}/${spaceType}`"
      class="flex items-center nested-link"
    >
      <b-icon name="arrow-left" class="link filters-arrow" />
      <p class="t-p3 link">Вернуться к результатам поиска</p>
    </a>

    <div
      class="flex items-center cursor-pointer border-0 nested-link"
      @click="toggleFilter"
    >
      <b-icon
        :class="{ 'rotate-180': isFilter }"
        name="arrow-down"
        class="filters-arrow link"
      />

      <p class="t-p3 link filters-edit">Изменить параметры поиска</p>
    </div>
  </div>

  <client-only>
    <the-filter v-show="isFilter" class="mt-12 transition" />
  </client-only>
</template>

<script setup lang="ts">
import { computed, ref } from 'vue'
import BIcon from '@/components/base/icon/BIcon.vue'
import TheFilter from '@/components/filter/TheFilter.vue'
import ClientOnly from '@/components/base/ClientOnly.vue'
import { usePageContext } from '~/renderer/utils'
import { getSpace } from '@/utils/filter'

const isFilter = ref(false)

const toggleFilter = () => {
  isFilter.value = !isFilter.value
}

const context = usePageContext()
const spaceType = computed(
  () => getSpace(context.routeParams.space, 'type')?.type
)
</script>

<style lang="postcss" scoped>
.filters {
  @apply bg-white rounded select-none;
  @apply flex mobile:flex-col mobile:gap-12 md:justify-between;
  @apply p-16 md:px-26 lg:px-30 lg:py-20;

  > p {
    @apply mb-0;
  }

  &-edit {
    @apply border-b border-dashed border-black/50 ml-10;
  }

  &-arrow {
    @apply transition min-w-fit border-0 hidden lg:block;
  }
}
</style>
