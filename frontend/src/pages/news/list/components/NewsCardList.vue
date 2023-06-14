<template>
  <div class="card-list">
    <b-card-news-alt
      v-for="(item, idx) in items"
      :key="idx"
      :style="`order: ${idx + 1}`"
      :news="item"
      :class="{
        'is-expanded': idx === 0,
        'is-expanded--desktop': idx === LIMIT - 1,
        'lg-only:!w-full': idx === 9
      }"
    />
    <person-card
      v-if="!!items.length"
      :person="person"
      class="order-6 md:order-2 lg:order-1"
    />
    <template v-if="items.length > 7">
      <project-card-special
        variant="research"
        :item="research"
        class="order-3 md:order-7"
      />
      <form-subscribe-project
        variant="card"
        :crm-id="0"
        class="order-6 md:order-7"
      />
    </template>
    <project-card-special
      v-if="items.length > 9"
      variant="service"
      :item="service"
      class="order-10 lg-only:order-6"
    />
  </div>
</template>

<script setup lang="ts">
import type { INews, IResearch, IServiceItem, IPerson } from '@/types'
import FormSubscribeProject from '@/components/form/project/FormSubscribeProject.vue'
import ProjectCardSpecial from '@/pages/projects/list/components/ProjectCardSpecial.vue'
import BCardNewsAlt from '@/components/base/card/BCardNewsAlt.vue'
import PersonCard from '@/components/person/card/PersonCard.vue'
import { LIMIT } from '@/config/news.config'
import { PHONE } from '@/config/main.config'

defineProps<{
  items: INews[]
  service: IServiceItem
  research: IResearch
  currentPage: number
}>()

const person: IPerson = {
  name: 'Екатерина Прокопова',
  image: '/images/staff/newsDirector.jpg',
  jobTitle: 'Руководитель направления по связям с общественностью',
  phone: PHONE,
  email: 'ekaterina.prokopova@core-xp.ru'
}
</script>

<style lang="postcss" scoped>
.card-list {
  @apply flex gap-16 md:gap-20 xl:gap-30 flex-wrap mt-20 md:mt-30 xl:mt-40;

  .is-expanded {
    @apply md:w-full;

    :deep(.info-description) {
      @apply mobile:!hidden md:mt-16 xl:mt-20 overflow-hidden whitespace-normal;
      display: -webkit-box;
      -webkit-line-clamp: 3;
      -webkit-box-orient: vertical;
    }
    :deep(.info-title) {
      @apply md:t-h2;
    }
  }

  .is-expanded,
  .is-expanded--desktop {
    @screen lg {
      @apply bg-none w-[clamp(50%,59vw,900px)] h-[550px] flex p-30;

      /*TODO: костыль, стоит перенести link в tailwind */
      :deep(.link) {
        @apply border-white text-white border-opacity-50;
        @apply hover:text-white hover:border-white hover:border-opacity-100;
      }

      &:hover {
        :deep(.link) {
          @apply text-white border-white border-opacity-100;
        }
      }

      background: linear-gradient(
        180deg,
        rgba(0, 0, 0, 0) 0%,
        rgba(0, 0, 0, 0.7) 100%
      );

      :deep(img) {
        @apply absolute inset-0 !w-full !h-full object-cover object-center -z-10 mb-0;
      }

      :deep(.info-date) {
        @apply text-white;
      }

      :deep(.info-description) {
        -webkit-line-clamp: 3;
      }
    }
  }
}
</style>
