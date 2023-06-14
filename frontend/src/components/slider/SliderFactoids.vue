<template>
  <div class="marquee-container" :style="`--gap-2xl: ${gap}px;`">
    <marquee-text v-if="MarqueeText" :duration="duration">
      <div class="marquee">
        <div
          v-for="(item, idx) in items"
          :key="idx"
          class="card-factoid"
          :class="`card-factoid--${variant}`"
        >
          <b-badge
            v-if="item.tag && variant === 'about'"
            variant="outline-gray"
            >{{ item.tag }}</b-badge
          >
          <div class="title">{{ showStringWithSquare(item.name) }}</div>
          <div class="subtitle">{{ item.previewText }}</div>
        </div>
      </div>
    </marquee-text>

    <div v-else>
      <div class="marquee-text-wrap">
        <div class="marquee-text-content">
          <div class="marquee-text-text">
            <div class="marquee w-max">
              <div
                v-for="(item, idx) in items"
                :key="idx"
                class="card-factoid"
                :class="`card-factoid--${variant}`"
              >
                <b-badge
                  v-if="item.tag && variant === 'about'"
                  variant="outline-gray"
                  >{{ item.tag }}</b-badge
                >
                <div class="title">{{ showStringWithSquare(item.name) }}</div>
                <div class="subtitle">{{ item.previewText }}</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { IFactoid } from '@/types'
import 'swiper/css'
import 'swiper/css/autoplay'
import { computed, defineAsyncComponent, onMounted, shallowRef } from 'vue'
import { showStringWithSquare } from '@/utils/show'
import BBadge from '@/components/base/badge/BBadge.vue'

const props = defineProps<{
  items: IFactoid[]
  secPerFactoid: number
  gap: number
  variant: 'about' | 'home'
}>()

const MarqueeText = shallowRef()

// Подключаем библиотеку только для клиента
onMounted(() => {
  MarqueeText.value = defineAsyncComponent(
    () =>
      // @ts-ignore
      import('vue-marquee-text-component')
  )
})

//Длительность прокрутки всех картинок - кол-во картинок * кол-во секунд на 1 картинк
const duration = computed(() => props.secPerFactoid * props.items.length)
</script>
<style src="@/assets/styles/components/card-factoid.css" scoped />

<style lang="postcss" scoped>
.marquee-container {
  --gap: 16px;
  --gap-md: 20px;

  @apply flex flex-row items-center relative w-full overflow-hidden
  gap-[var(--gap)] md:gap-[var(--gap-md)] 2xl:gap-[var(--gap-2xl)];

  .marquee {
    @apply flex flex-row flex-none items-center min-w-full;
    @apply gap-[var(--gap)] md:gap-[var(--gap-md)] 2xl:gap-[var(--gap-2xl)]
    ml-[var(--gap)] md:ml-[var(--gap-md)] 2xl:ml-[var(--gap-2xl)];
  }
}
</style>
