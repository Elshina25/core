<template>
  <div class="mt-80 md:mt-96 2xl:mt-150">
    <swiper slides-per-view="auto" class="research-slider">
      <template #container-start>
        <b-slider-head :title="title" />
      </template>
      <swiper-slide v-for="(item, idx) in items" :key="idx">
        <b-card-research
          class="research-slider__item"
          :research="item"
          @lock="requestResearch"
        />
      </swiper-slide>
    </swiper>

    <div class="container">
      <a class="link t-p2" :href="$ROUTE.RESEARCHES.slug">Все исследования</a>
    </div>

    <b-modal v-model="modal.status" container background>
      <form-request-research
        :research="lockResearch"
        @success="modal.close()"
      />
    </b-modal>
  </div>
</template>

<script lang="ts" setup>
import { Ref, ref } from 'vue'
import { IResearch } from '@/types'
import { useModal } from '@/composables/useModal'
import { Swiper, SwiperSlide } from 'swiper/vue'
import BModal from '@/components/base/modal/BModal.vue'
import BCardResearch from '@/components/base/card/BCardResearch.vue'
import BSliderHead from '@/components/base/slider/BSliderHead.vue'
import 'swiper/css'
import FormRequestResearch from '@/components/form/research/FormRequestResearch.vue'

const props = withDefaults(
  defineProps<{
    title?: string
    items: IResearch[]
  }>(),
  {
    title: 'Читайте также на сайте'
  }
)

const modal = useModal()
const lockResearch: Ref<IResearch> = ref(props.items[0])

/**
 * Открываем модалку для получения исследований
 */
const requestResearch = (research: IResearch) => {
  lockResearch.value = research
  modal.open()
}
</script>

<style src="@/components/base/slider/slider.css" scoped />
<style lang="postcss" scoped>
.research-slider {
  @apply mb-20 md:mb-30 xl:mb-60;

  &__item {
    @apply !w-[270px] h-[270px] md:!w-[340px] md:h-[340px] xl:!w-[420px] xl:h-[420px];

    :deep(.card__labels) {
      @apply flex-col items-start;

      .card__labels-time {
        @apply mt-20;
      }
    }
  }
}
</style>
