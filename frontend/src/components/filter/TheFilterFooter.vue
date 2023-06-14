<template>
  <div
    class="flex mobile:flex-col content-between md:items-center"
    :class="{ 'mt-24 md:mt-40 xl:mt-50': show }"
  >
    <b-filter-button
      variant="link"
      type="div"
      label="Доп. фильтры"
      class="flex items-center"
      :class="{
        'mobile:order-1 mobile:mt-12': show,
        'mobile:mb-16': !show
      }"
      @click="toggle"
    >
      <span v-show="!show" class="md-only:hidden">
        Дополнительные фильтры
      </span>
      <span v-show="!show" class="hidden md-only:block"> Доп. фильтры </span>
      <span v-show="show">Свернуть фильтр</span>
      <template #after>
        <b-icon
          class="ml-8 w-20"
          :class="{ 'rotate-180 mt-6': show }"
          name="arrow-down"
        />
      </template>
    </b-filter-button>

    <div class="flex mobile:w-full mobile:flex-col ml-auto">
      <a
        :href="showOnMapLink"
        class="filter-btn filter-btn--grey"
        :class="{ 'filter-btn--loading': loader.status }"
        @click="loader.setVariant('map')"
      >
        <div v-if="loader.isMap && loader.status" class="spinner"></div>
        <span v-else>Показать на карте</span>
      </a>
      <a
        :href="showOfferListLink"
        class="filter-btn filter-btn--green mobile:order-0"
        :class="{ 'filter-btn--loading': loader.status }"
        @click="loader.setVariant('list')"
      >
        <div v-if="loader.isList && loader.status" class="spinner"></div>
        <span v-else>Показать предложения</span>
      </a>
    </div>
  </div>
</template>

<script lang="ts" setup>
import BIcon from '@/components/base/icon/BIcon.vue'
import BFilterButton from './base/button/BFilterButton.vue'
import { useModelValue } from '@/composables/useModelValue'
import { useFilterStoreGenerator } from '@/composables/filter/useFilterLinkGenerator'
import { useFilterLoaderStore } from '@/stores/filter/loader'

defineEmits(['update:modelValue'])

const props = defineProps<{
  modelValue: boolean
}>()

const loader = useFilterLoaderStore()
const { showOfferListLink, showOnMapLink } = useFilterStoreGenerator()

const { value: show, setValue } = useModelValue(props)
const toggle = () => setValue(!show.value)
</script>

<style lang="postcss" scoped>
.filter-btn {
  &--green {
    @apply flex items-center justify-center md:h-[54px] md:w-[296px] xl:h-60 xl:w-[355px];
  }

  &--grey {
    @apply flex items-center justify-center 
      mobile:mt-10 mr-10 mobile:order-1 md:w-[210px] xl:w-[247px] md:h-[54px] xl:h-60;
  }

  &--loading {
    @apply pointer-events-none;
  }
}
.spinner {
  @apply inline-block w-32 h-32;

  &::after {
    @apply content-[''] block w-32 h-32 rounded-[80%] border-2
    border-t-white border-r-transparent border-b-white border-l-transparent animate-spin;
  }
}
</style>

<style src="./base/button/filter-button.css" scoped></style>
