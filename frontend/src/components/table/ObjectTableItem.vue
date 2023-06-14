<template>
  <div class="row" @click="toggleCollapse">
    <b-icon
      name="arrow-down"
      :class="{ 'rotate-180': !isCollapsed }"
      class="row-arrow"
    />
    <div class="row__body">
      <div class="row__body-first">
        <div class="row__purpose">
          <div class="t-p1 md:t-p2 mobile:mb-4 overflow-hidden text-ellipsis">
            {{ item.type }}
          </div>

          <div class="t-p1 md:t-p2 md:ml-28 md:hidden">{{ item.size }} м²</div>
        </div>
      </div>

      <div class="row__body-last" :class="{ collapsed: isCollapsed }">
        <div class="row__body__cell">
          <div class="row__body__cell-label">Состояние</div>
          <div class="row__body__cell-value">{{ item.condition }}</div>
        </div>

        <div class="row__body__cell">
          <div class="row__body__cell-value">{{ item.size }} м²</div>
        </div>

        <div class="row__body__cell">
          <div class="row__body__cell-label">Этаж</div>
          <div class="row__body__cell-value">
            {{ item.floorNumber }} <span class="hidden md:inline">этаж</span>
          </div>
        </div>

        <b-button
          variant="green"
          class="row-btn"
          @click="emit('requestPrice', item.crmId)"
          >Узнать цену</b-button
        >

        <!--        <a href="#" class="row-link link">Подробнее</a>-->
      </div>
    </div>
  </div>
</template>

<script lang="ts" setup>
import BIcon from '@/components/base/icon/BIcon.vue'
import BButton from '@/components/base/button/BButton.vue'
import { ref } from 'vue'
import { IObjectTableItem } from '@/types/objects'

const emit = defineEmits<{
  (e: 'requestPrice', event: IObjectTableItem['crmId']): void
}>()

defineProps<{
  item: IObjectTableItem
}>()

const isCollapsed = ref(true)

const toggleCollapse = () => (isCollapsed.value = !isCollapsed.value)
</script>

<style scoped lang="postcss">
.row {
  @apply bg-white rounded p-16 md:p-10 xl:p-30 flex relative;

  &__image {
    @apply w-60 h-60 md:w-80 md:h-80 xl:w-100 xl:h-100 bg-cover bg-center resize-none;
    @apply mr-12 md:mr-40 xl:mr-60 rounded bg-white;
  }

  &__purpose {
    @apply flex mobile:flex-col md-only:justify-between text-ellipsis whitespace-nowrap;
    @apply max-w-[200px] md:min-w-[136px] md:max-w-[136px] xl:min-w-full xl:max-w-full xl:mx-16;
  }

  &__body {
    @apply w-full flex mobile:flex-col  md:justify-between md:items-center;

    &-first {
      @apply flex items-center;

      > div {
        @apply t-p3 md-only:t-p2;
      }
    }

    &-last {
      @apply flex mobile:flex-col mobile:mt-16 items-center md:items-baseline md:justify-end;

      &.collapsed {
        @apply mobile:hidden;
      }
    }

    &__cell {
      @apply w-full  xl:items-center mb-12 md:min-w-[136px] md:max-w-[136px] xl:min-w-[140px] xl:max-w-[140px] xl:mr-26;
      @apply mobile:flex mobile:justify-between;

      &-label {
        @apply w-1/2 md:hidden text-black/60;
      }

      &-value {
        @apply mobile:w-1/2 max-w-[142px] t-p1 md:t-p2;
        @apply text-ellipsis whitespace-nowrap overflow-hidden;
      }

      &-area {
        @apply hidden xl:flex;
      }
    }
  }

  &-arrow {
    @apply hidden mobile:block absolute top-[24px] right-16 transition;
    @apply w-18 h-18;
  }

  &-btn {
    @apply mobile:mb-10 mobile:mt-8 md:min-w-[168px] xl:min-w-[194px];
  }

  &-link {
    @apply t-p3 md-only:t-p2 md:ml-30 md:mr-16 xl:mr-24;
  }
}
</style>
