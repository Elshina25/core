<template>
  <div class="tab-content">
    <h3>{{ tab.title }}</h3>

    <div class="tab-content__tags">
      <a
        v-for="(tag, idx) in tab.values"
        v-show="isShow(idx)"
        :key="idx"
        :href="tag.path"
        class="link tab-content__tag"
        >{{ tag.name }}</a
      >
    </div>

    <div
      v-if="showBtnAll"
      class="tab-content__toggler nested-link"
      @click="showAll = !showAll"
    >
      <span class="link link-button">{{
        showAll ? 'Свернуть список' : 'Раскрыть список'
      }}</span
      ><b-icon
        name="arrow-down-rounded"
        class="link link-icon"
        :class="{ 'top-2 rotate-180': showAll }"
      />
    </div>
  </div>
</template>

<script lang="ts" setup>
import { IObjectTab } from '@/types'
import { useShowAll } from '@/composables/useShowAll'
import BIcon from '@/components/base/icon/BIcon.vue'

const props = defineProps<{
  tab: IObjectTab
}>()

const { showAll, showBtnAll, isShow } = useShowAll(props.tab.values.length, 8)
</script>

<style lang="postcss" scoped>
.tab-content {
  @apply mt-20 p-16 md:mt-40 md:p-26 xl:p-60 bg-white rounded;

  &__tags {
    @apply grid grid-cols-2 gap-16 mt-16 overflow-hidden
      md:mt-20 md:gap-20 md:grid-cols-3 
      xl:grid-cols-4 xl:gap-30 xl:mt-40;
  }

  &__tag {
    @apply t-p2 w-fit;
  }

  &__toggler {
    @apply inline-flex items-center gap-4 t-p3 md:t-p2 relative mt-20 xl:mt-40 cursor-pointer;

    svg {
      @apply relative w-18 h-18 md:w-20 md:h-20 xl:w-24 xl:h-24;
    }
  }
}
</style>
