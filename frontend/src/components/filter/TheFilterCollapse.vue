<template>
  <div class="collapse-filters" :class="{ active: show, '-mt-6': !show }">
    <div v-show="show">
      <h3 class="collapse-filters__title">Дополнительные фильтры</h3>
      <b-filter-form-generator :fields="FILTER_FORM_COLLAPSE" />
    </div>
    <the-filter-footer v-model="show" />
  </div>
</template>

<script lang="ts" setup>
import BFilterFormGenerator from './base/form-generator/BFilterFormGenerator.vue'
import TheFilterFooter from './TheFilterFooter.vue'

import { FILTER_FORM_COLLAPSE } from '@/config/filter/filter-form.config'
import { ref } from 'vue'

const show = ref(false)
</script>

<style lang="postcss" scoped>
.collapse-filters {
  @apply absolute w-full px-16 xl:px-20 pb-20
    h-[var(--filter-collapse-h)] md:h-[var(--filter-collapse-h-md)] xl:h-[var(--filter-collapse-h-xl)]
    bg-white rounded-b z-20;

  &.active {
    @apply h-fit mt-12 pt-16 rounded;
  }

  &__title {
    @apply text-15 md:text-18 xl:text-20;
  }
}

:deep(.collapse-filters) {
  &__group {
    @apply block md:flex mobile:flex-wrap md:items-baseline mt-24
      md:mt-28 xl:mt-30;

    &--desktop {
      @apply hidden xl:flex mt-34;
    }

    .filter-label {
      @apply mr-0 mobile:block mobile:mb-12 md:mr-32 xl:mr-30;
    }
  }

  &__checkbox-group {
    @apply md:flex md:flex-wrap xl:mr-[110px];
  }

  &__checkbox {
    @apply first:rounded-l first:rounded-r-none last:rounded-r last:rounded-l-none
    [&:nth-child(1)]:-mr-1 md:[&:nth-child(3)]:-ml-1;

    @screen mobile {
      @apply [&:nth-child(1)]:float-left [&:nth-child(2)]:rounded-r [&:nth-child(3)]:rounded mb-8;
    }
  }
}
</style>
