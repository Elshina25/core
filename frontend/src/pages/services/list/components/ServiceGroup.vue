<template>
  <div :id="group.code" class="service-group">
    <div class="service-group__head" :class="headClass">
      <h2>{{ group.name }}</h2>
    </div>
    <slot></slot>
    <div class="service-group__categories">
      <a
        v-for="item in group.items"
        :key="item.id"
        :href="getServiceSlug(item.code, item.fastLink)"
        class="nested-link w-fit"
      >
        <span class="link">
          {{ item.name }}
        </span>
      </a>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { computed } from 'vue'
import { getServiceSlug } from '@/utils/show'
import type { IServiceGroup } from '@/types'

const props = defineProps<{
  group: IServiceGroup
}>()

const headClass = computed<string>(() =>
  props.group.code ? `service-group__head--${props.group.code}` : ''
)
</script>

<style lang="postcss" scoped>
.service-group {
  &__head {
    @apply min-h-[90px] md:min-h-[110px] lg:min-h-[198px]
    p-26 lg:p-40 bg-[#E5EBED] rounded bg-cover bg-no-repeat bg-center;

    &--consulting {
      @apply bg-[url('/images/service/sm/large/consulting.jpg')]
        md:bg-[url('/images/service/md/large/consulting.jpg')]
        lg:bg-[url('/images/service/lg/large/consulting.jpg')];
    }

    &--accompaniment {
      @apply bg-[url('/images/service/sm/large/accompaniment.jpg')]
        md:bg-[url('/images/service/md/large/accompaniment.jpg')]
        lg:bg-[url('/images/service/lg/large/accompaniment.jpg')];
    }

    &--control {
      @apply bg-[url('/images/service/sm/large/control.jpg')]
        md:bg-[url('/images/service/md/large/control.jpg')]
        lg:bg-[url('/images/service/lg/large/control.jpg')];
    }

    &--exploitation {
      @apply bg-[url('/images/service/sm/large/exploitation.jpg')]
        md:bg-[url('/images/service/md/large/exploitation.jpg')]
        lg:bg-[url('/images/service/lg/large/exploitation.jpg')];
    }

    &--selection {
      @apply bg-[url('/images/service/sm/large/selection.jpg')]
        md:bg-[url('/images/service/md/large/selection.jpg')]
        lg:bg-[url('/images/service/lg/large/selection.jpg')];
    }
  }

  &__categories {
    @apply t-p1 grid md:grid-cols-2 gap-16 md:gap-26 lg:gap-30 p-16
      mt-10 lg:mt-20 md:p-26 lg:p-60 bg-white rounded;
  }
}
</style>
