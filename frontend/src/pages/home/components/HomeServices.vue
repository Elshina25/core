<template>
  <div class="container mt-80 md:mt-72 2xl:mt-150">
    <h2 class="t-h1 mb-30 md:mb-40 2xl:mb-60">Наши услуги</h2>

    <div class="services">
      <div
        v-for="item in items"
        :key="item.id"
        class="services-card"
        :class="`services-card--${item.code}`"
      >
        <a
          :href="$ROUTE.SERVICES.slug + `/#${item.code}`"
          class="services-card__title nested-link"
        >
          <span class="link link-green">{{ item.name }}</span>
        </a>
        <p class="services-card__description" v-html="item.description"></p>
      </div>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { IServiceGroup } from '@/types'

defineProps<{
  items: IServiceGroup[]
}>()
</script>

<style lang="postcss" scoped>
.services {
  @apply grid grid-cols-12 gap-16 md:gap-20 lg:gap-30;

  &-card {
    @apply p-16 lg:p-30 h-[268px] lg:h-[480px] bg-[#E5EBED] rounded
      bg-cover bg-no-repeat bg-center col-span-12 md:col-span-4;

    @screen mobile {
      @apply bg-right-bottom bg-[length:calc(min(400px,100%))];
    }

    &:nth-child(4),
    &:nth-child(5) {
      @apply md:col-span-6 h-[250px] md:h-[250px] lg:h-[448px];
    }

    &--accompaniment {
      @apply bg-[url('/images/service/sm/accompaniment.jpg')]
        md:bg-[url('/images/service/md/accompaniment.jpg')]
        lg:bg-[url('/images/service/lg/accompaniment.jpg')];
    }

    &--consulting {
      @apply bg-[url('/images/service/sm/consulting.jpg')]
        md:bg-[url('/images/service/md/consulting.jpg')]
        lg:bg-[url('/images/service/lg/consulting.jpg')];
    }

    &--control {
      @apply bg-[url('/images/service/sm/control.jpg')]
        md:bg-[url('/images/service/md/control.jpg')]
        lg:bg-[url('/images/service/lg/control.jpg')];
    }

    &--exploitation {
      @apply bg-[url('/images/service/sm/exploitation.jpg')]
        md:bg-[url('/images/service/md/exploitation.jpg')]
        lg:bg-[url('/images/service/lg/exploitation.jpg')];
    }

    &--selection {
      @apply bg-[url('/images/service/sm/selection.jpg')]
        md:bg-[url('/images/service/md/selection.jpg')]
        lg:bg-[url('/images/service/lg/selection.jpg')];
    }

    &__title {
      @apply inline-block t-h2 md:t-h3;
    }

    &__description {
      @apply t-p2 md:t-p3 mt-12 max-h-[80%] overflow-auto;

      @screen lg {
        @apply text-18 leading-150 font-regular mt-20;
      }

      @screen xl {
        @apply text-20 leading-150 font-regular mt-20;
      }
    }
  }
}
</style>
