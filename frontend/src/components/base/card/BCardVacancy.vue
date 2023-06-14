<template>
  <a :href="item.link" target="_blank" class="card-container">
    <div class="card">
      <div class="card-left">
        <div class="card-name">
          {{ item.name }}
        </div>
        <div class="link t-p2 mobile:hidden w-fit">Смотреть вакансию</div>
      </div>

      <div class="card-right">
        <div class="card-salary">
          {{ salaryLabel }}
        </div>

        <div class="card-address">{{ addressLabel }}</div>
      </div>

      <div class="link t-p2 md:hidden w-fit">Смотреть вакансию</div>
    </div>
  </a>
</template>

<script lang="ts" setup>
import { IVacancy } from '@/types'
import { computed } from 'vue'

const props = defineProps<{
  item: IVacancy
}>()

const addressLabel = computed(
  () => props.item.city + (props.item.metro ? `, м. ${props.item.metro}` : '')
)

const salaryLabel = computed<string>(() => {
  if (!props.item.salaryFrom && !props.item.salaryTo) {
    return 'з/п не указана'
  }

  const minLabel = props.item.salaryFrom ? `от ${props.item.salaryFrom} ` : ''
  const maxLabel = props.item.salaryTo ? `до ${props.item.salaryTo} ` : ''
  return minLabel + maxLabel + 'руб.'
})
</script>

<style scoped lang="postcss">
.card {
  @apply flex mobile:flex-col gap-20 md:gap-40 w-full;

  &-container {
    @apply rounded bg-auxiliary-6/50 md:min-h-[166px] 2xl:min-h-[186px] flex 2xl:items-center
    hover:text-white hover:bg-green p-16 md:p-30 2xl:px-40 2xl:py-30;

    &:hover {
      .link {
        @apply text-white border-white/50 hover:text-white hover:border-white;
      }
    }

    .link {
      @apply transition-none;
    }
  }

  &-right {
    @apply w-full flex md-only:flex-col-reverse flex-col 2xl:flex-row justify-between 2xl:justify-start mobile:gap-20 md:gap-40;
  }

  &-left {
    @apply flex flex-col md:gap-20;
  }

  &-name {
    @apply w-full md:w-[437px] 2xl:min-w-[605px] 2xl:max-w-[605px] t-p1;
  }

  &-salary {
    @apply t-p2 2xl:w-[250px];
  }

  &-address {
    @apply t-p2 2xl:w-[305px];
  }
}
</style>
