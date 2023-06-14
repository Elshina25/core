<template>
  <a
    class="card-what-new nested-link"
    :href="item.slug"
    :class="{ 'card-what-new-image': item.image }"
  >
    <img :src="image" :title="item.name" alt="" />
    <b-badge class="tag">{{ tagMapping[item.type] }}</b-badge>
    <div class="info">
      <span class="relative">
        <span class="card-what-new-title link link-white text-ellipsis">
          {{ item.name }}
        </span>
      </span>
    </div>
  </a>
</template>

<script lang="ts" setup>
import { getFullUrl } from '@/utils'
import { IWhatNewCard } from '@/types'
import BBadge from '@/components/base/badge/BBadge.vue'
import { computed } from 'vue'

const props = defineProps<{
  item: IWhatNewCard
}>()

const tagMapping: Record<IWhatNewCard['type'], string> = {
  blog: 'Блог',
  news: 'Новости',
  research: 'Специальный отчет',
  service: 'Услуга'
}

const image = computed(() => {
  if (props.item.image) {
    return getFullUrl(props.item.image)
  } else {
    return props.item.type === 'research'
      ? '/images/research/card-bg.png'
      : '/images/service/card/card-bg.svg'
  }
})
</script>

<style scoped lang="postcss">
.card-what-new {
  @apply relative flex flex-col p-16 xl:p-30;
  @apply bg-cover bg-center rounded overflow-hidden cursor-pointer;
  @apply w-[270px] h-[270px] md:w-[340px] md:h-[340px] xl:w-[420px] xl:h-[420px];

  &-image {
    background: linear-gradient(0deg, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.7));
  }

  img {
    @apply absolute inset-0 w-full h-full object-cover object-center -z-10;
  }

  .tag {
    @apply mb-26 xl:mb-38 max-w-fit;
  }

  &-title {
    @apply t-h3 xl:t-h4 relative;
    word-break: break-word; /* TODO: попробовать установить глобально */
  }
}
</style>
