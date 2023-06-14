<template>
  <a :href="link" class="xl:w-[390px] md:w-[calc(50%-10px)] w-full">
    <div class="card" :class="`card--${variant}`">
      <div class="badges">
        <template v-if="variant === 'research'">
          <b-badge v-if="item?.section">{{ item.section }}</b-badge>
          <b-badge v-if="item?.type">{{ item.type }}</b-badge>
        </template>
        <template v-else>
          <b-badge>Услуга</b-badge>
        </template>
      </div>

      <div class="info">
        <span class="relative">
          <span class="info-title link link-white">
            {{ item.name }}
          </span>
        </span>
        <div v-if="variant === 'research' && item?.date" class="info-date">
          {{ item.date }}
        </div>
      </div>
    </div>
    <div class="t-h3 hidden xl:block">{{ item.name }}</div>
  </a>
</template>

<script lang="ts" setup>
import { IResearch, IServiceGroup } from '@/types'
import BBadge from '@/components/base/badge/BBadge.vue'
import { computed } from 'vue'
import { ROUTE } from '@/routes'

type Variant = 'research' | 'service'

const props = defineProps<{
  item: IResearch & IServiceGroup
  variant: Variant
}>()

const link = computed(() => {
  switch (props.variant) {
    case 'research':
      return ROUTE.RESEARCHES.slug + `/${props.item.slug}`
    case 'service':
      return ROUTE.SERVICES.slug + `/#${props.item.code}`
    default:
      return ''
  }
})
</script>

<style lang="postcss" scoped>
.card {
  @apply relative flex flex-col justify-between xl:mb-20 p-16 xl:p-30
  w-full h-[288px] md:h-[358px] xl:h-[390px] xl:rounded-[40px]
  bg-cover bg-center rounded overflow-hidden cursor-pointer;

  .badges {
    @apply flex flex-wrap gap-6 xl:gap-10;
  }

  .info {
    @apply flex flex-col md-only:mr-[20%] xl:hidden;

    &-title {
      @apply t-h3 relative;
      word-break: break-word; /* TODO: попробовать установить глобально */
    }

    &-date {
      @apply mt-16 xl:mt-20 opacity-60 text-white t-date whitespace-nowrap float-right;
    }
  }

  &--research {
    @apply bg-[url('/images/research/card-bg-mobile.png')]
    md:bg-[url('/images/research/card-bg-tablet.png')]
    xl:bg-[url('/images/research/card-bg.png')];
  }

  &--service {
    @apply bg-[url('/images/service/card/card-bg.svg')] rounded-[20px] xl:rounded-[40px];
  }
}
</style>
