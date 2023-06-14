<template>
  <div class="marquee-container">
    <marquee-text v-if="MarqueeText" :duration="duration">
      <div class="marquee">
        <img
          v-for="(client, idx) in items"
          :key="idx"
          :src="getFullUrl(client.picture)"
        />
      </div>
    </marquee-text>

    <div v-else>
      <div class="marquee-text-wrap">
        <div class="marquee-text-content">
          <div class="marquee-text-text">
            <div class="marquee">
              <img
                v-for="(client, idx) in items"
                :key="idx"
                :src="getFullUrl(client.picture)"
              />
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { computed, defineAsyncComponent, onMounted, shallowRef } from 'vue'
import { getFullUrl } from '@/utils'
import { IClient } from '@/types'

const props = defineProps<{
  secPerClient: number
  items: IClient[]
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
const duration = computed(() => props.secPerClient * props.items.length)
</script>

<style lang="postcss" scoped>
.marquee-container {
  --gap: 66px;
  --gap-md: 66px;
  --gap-2xl: 80px;

  @apply h-80 md:h-100 2xl:h-120 flex flex-row items-center relative w-full overflow-hidden
    gap-[var(--gap)] md:gap-[var(--gap-md)] 2xl:gap-[var(--gap-2xl)];

  .marquee {
    @apply flex flex-row flex-none items-center min-w-full;
    @apply gap-[var(--gap)] md:gap-[var(--gap-md)] 2xl:gap-[var(--gap-2xl)]
      ml-[var(--gap)] md:ml-[var(--gap-md)] 2xl:ml-[var(--gap-2xl)];
  }
}
</style>
