<template>
  <div class="service-list">
    <a
      :href="$ROUTE.SERVICES.slug + `/#${item.code}`"
      class="service-list__title nested-link"
    >
      <span class="link link-green">{{ item.name }}</span>
    </a>
    <div class="service-list-items">
      <a
        v-for="service in item.items"
        :key="service.id"
        :href="getServiceSlug(service.code, service.fastLink)"
        class="service-list-items__item nested-link"
      >
        <span class="link">
          {{ service.name }}
        </span>
      </a>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { IServiceGroup } from '@/types'
import { getServiceSlug } from '@/utils/show'

defineProps<{
  item: IServiceGroup
}>()
</script>

<style lang="postcss" scoped>
.service-list {
  @apply p-16 md:p-26 lg:p-30 bg-[#E5EBED] rounded;

  &__title {
    @apply inline-block t-h3 mb-14 md:mb-18 lg:mb-22;
  }

  &-items {
    @screen md-only {
      @apply grid grid-cols-2 gap-18;
    }

    &__item {
      @apply block w-fit t-p2;
    }

    &__item:not(:last-child) {
      @apply mb-12 md:mb-0 lg:mb-20;
    }
  }
}
</style>
