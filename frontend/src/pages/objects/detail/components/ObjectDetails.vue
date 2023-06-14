<template>
  <div class="detail">
    <div class="detail-head">
      <div class="t-p3">ID: {{ object.id }}</div>
      <b-badge v-if="object.for" variant="outline-green">{{
        object.for
      }}</b-badge>
    </div>

    <div class="detail__body">
      <div class="detail__body-first">
        <div class="detail__body-address">
          {{ object.address }}
          <!--          <template v-for="(address, idx) in item.address" :key="idx">-->
          <!--            <div class="t-p3 mb-2">-->
          <!--              <component-->
          <!--                :is="address.isLink ? 'a' : 'span'"-->
          <!--                :class="{ link: address.isLink }"-->
          <!--                href="#"-->
          <!--              >-->
          <!--                {{ `${address.name}` }}-->
          <!--              </component>-->

          <!--              <span-->
          <!--                v-if="idx !== item.address.length - 1"-->
          <!--                class="pr-10 border-b-0"-->
          <!--                >,</span-->
          <!--              >-->
          <!--            </div>-->
          <!--          </template>-->
        </div>

        <div v-if="object.metro" class="detail__body-station">
          <station-item :station="object.metro" />
        </div>
        <b-button
          class="hidden md-only:block w-[223px]"
          variant="green"
          @click="scrollTo(`#${OBJECT_REQUEST_FORM_ID}`)"
        >
          Заявка на просмотр</b-button
        >
      </div>

      <div class="detail__body-second">
        <div class="detail__body-description">
          <template v-for="({ label, value }, idx) in info" :key="idx">
            <div class="t-caption text-black/60 my-auto lg:max-w-[160px]">
              {{ label }}
            </div>
            <div class="t-p3">{{ value }}</div>
          </template>
        </div>

        <b-button
          class="md-only:hidden mt-16 lg:mt-32"
          variant="green"
          @click="scrollTo(`#${OBJECT_REQUEST_FORM_ID}`)"
        >
          Заявка на просмотр
        </b-button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { KEY_OBJECT } from '@/pages/objects/detail/injectionKeys'
import { OBJECT_REQUEST_FORM_ID } from '@/config/objects.config'
import { injectStrict, scrollTo } from '@/utils'
import { getObjectPrice } from '@/utils/show'
import { getSpace } from '@/utils/filter'
import BBadge from '@/components/base/badge/BBadge.vue'
import BButton from '@/components/base/button/BButton.vue'
import StationItem from '@/components/station/StationItem.vue'

const object = injectStrict(KEY_OBJECT)

const info = computed(() => {
  const space = getSpace(object.value.propertyType.id, 'id')

  const spaceName = space?.name
  const spaceClass = object.value.class
  const buildingTotal = `${object.value.buildingTotal} м²`
  const leaseTotal = `${object.value.leaseTotal} м²`
  const leaseSquare = `${object.value.leaseSquare} м²`
  const leasePrice = getObjectPrice(object.value.leasePrice)
  const salePrice = getObjectPrice(object.value.salePrice)
  const distanceFromCity = object.value.distanceFromCity + ' км'

  switch (space?.type) {
    case 'office':
      return [
        { label: 'Тип помещения', value: spaceName },
        { label: 'Класс здания', value: spaceClass },
        { label: 'Арендуемая площадь', value: leaseTotal },
        { label: 'Площади в аренду', value: leaseSquare },
        { label: 'Арендная ставка', value: leasePrice.text },
        // { label: 'Площади на продажу', value: `${object.sellArea} м²` },
        { label: 'Стоимость продажи', value: salePrice.text }
      ]
    case 'industrial_logistics':
      return [
        { label: 'Тип помещения', value: spaceName },
        { label: 'Класс здания', value: spaceClass },
        { label: 'Общая площадь', value: buildingTotal },
        { label: 'Общая арендуемая площадь', value: leaseTotal },
        { label: 'Расстояние до МКАД', value: distanceFromCity }
        // { label: 'Статус', value: salePrice }
      ]
    case 'retail':
      return [
        { label: 'Тип помещения', value: spaceName },
        { label: 'Общая площадь', value: buildingTotal },
        { label: 'Общая арендуемая площадь', value: leaseTotal }
        // { label: 'Средняя ставка аренды', value: distanceFromCity },
        // { label: 'Дата открытия', value: salePrice }
      ]
    default:
      return [{ label: 'Тип помещения', value: spaceName }]
  }
})
</script>

<style scoped lang="postcss">
.detail {
  @apply bg-white rounded p-16 md:p-26 lg:p-30 h-fit;

  @screen lg {
    @apply min-w-[420px] max-w-[420px];
  }

  &-head {
    @apply flex items-end justify-between mb-16 md:mb-24 lg:mb-32;
  }

  &__body {
    @apply flex flex-col;

    @screen md-only {
      @apply flex-row gap-22;

      > div {
        @apply w-[calc(50%-11px)];
      }
    }

    &-first {
      @apply order-1 md-only:order-2;
    }

    &-second {
      @apply order-2 md-only:order-1;
    }

    &-address {
      @apply mb-16 md-only:mb-20 mobile:mr-40 flex flex-wrap;
    }

    &-station {
      @apply mb-20 md-only:mb-56 lg:mb-32 flex flex-col gap-10 md-only:flex-row;
    }

    &-description {
      @apply grid grid-cols-2 gap-y-8 md:gap-y-[11px] lg:gap-y-[8px] md-only:gap-x-22;
    }
  }
}
</style>
