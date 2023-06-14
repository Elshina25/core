<template>
  <div class="card-list">
    <b-card-project-alt
      v-for="(item, idx) in items"
      :key="idx"
      :style="`order: ${idx + 1}`"
      :project="item"
      :class="{ 'is-expanded': idx === 0 }"
    />
    <project-card-special
      v-if="!!items.length"
      variant="research"
      :item="research"
      class="order-1"
    />
    <template v-if="items.length > 5">
      <project-card-special variant="service" :item="service" class="order-5" />
      <form-subscribe-project variant="card" :crm-id="0" class="order-5" />
    </template>
  </div>
</template>

<script setup lang="ts">
import { IProject, IResearch, IServiceItem } from '@/types'
import FormSubscribeProject from '@/components/form/project/FormSubscribeProject.vue'
import BCardProjectAlt from '@/components/base/card/BCardProjectAlt.vue'
import ProjectCardSpecial from '@/pages/projects/list/components/ProjectCardSpecial.vue'

defineProps<{
  items: IProject[]
  service: IServiceItem
  research: IResearch
  currentPage: number
}>()
</script>

<style lang="postcss" scoped>
.card-list {
  @apply flex gap-20 xl:gap-30 flex-wrap mt-20 md:mt-30 xl:mt-40;

  .is-expanded {
    @apply mobile:h-[340px] md:w-full xl:w-[clamp(50%,59vw,900px)];
  }
}
</style>
