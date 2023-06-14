<template>
  <a :href="$ROUTE.SERVICES.slug + `/#${item.code}`" class="nested-link">
    <div class="card-service">
      <h2 class="title t-h2 link link-white">{{ item.name }}</h2>
      <p class="card-service__description" v-html="item.description"></p>
    </div>
  </a>
</template>

<script setup lang="ts">
import { IServiceGroup } from '@/types'

defineProps<{
  item: IServiceGroup
}>()
</script>

<style lang="postcss" scoped>
.card-service {
  @apply bg-green rounded w-[258px] h-[300px] p-16;

  @screen md {
    @apply w-[338px] h-[400px] p-26;
  }

  @screen lg {
    @apply w-[500px] h-[342px] p-30;
  }

  @screen xl {
    @apply w-[645px] h-[442px] p-30;
  }

  &__description {
    @apply t-p1 text-white mt-20;
  }
}
</style>
