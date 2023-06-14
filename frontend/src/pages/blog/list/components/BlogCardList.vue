<template>
  <div class="card-list">
    <b-card-blog
      v-for="(item, idx) in items"
      :key="idx"
      :style="`order: ${idx + 1}`"
      :blog="item"
      :class="{ 'is-expanded': idx === 0 }"
    />
    <project-card-special
      v-if="!!items.length"
      variant="research"
      :item="research"
      class="order-1"
    />
    <template v-if="items.length > 6">
      <project-card-special
        variant="service"
        :item="service"
        class="md-only:order-7 order-5"
      />
      <form-subscribe-project
        variant="card"
        :crm-id="0"
        class="md-only:order-4 order-5"
      />
    </template>
  </div>
</template>

<script setup lang="ts">
import { IBlog, IResearch, IServiceItem } from '@/types'
import FormSubscribeProject from '@/components/form/project/FormSubscribeProject.vue'
import ProjectCardSpecial from '@/pages/projects/list/components/ProjectCardSpecial.vue'
import BCardBlog from '@/components/base/card/BCardBlog.vue'

defineProps<{
  items: IBlog[]
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

    :deep(.info-description) {
      @apply mobile:!hidden md-only:!hidden md:mt-16 xl:mt-20 overflow-hidden whitespace-normal;
      display: -webkit-box;
      -webkit-line-clamp: 4;
      -webkit-box-orient: vertical;
    }

    :deep(.info-title) {
      @apply t-h2;
    }
  }
}
</style>
