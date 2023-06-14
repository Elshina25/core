<template>
  <div v-if="pageCount > 1" class="pagination">
    <b-icon
      name="arrow-prev"
      class="pagination-arrow"
      :class="{ disabled: currentPage === 1 }"
      @click="prev"
    />

    <button
      :class="{ active: currentPage === 1 }"
      class="pagination-item"
      @click="first"
    >
      1
    </button>
    <b-icon v-show="hasLeftDots" class="pagination-dots" name="dots" />

    <button
      v-for="(page, idx) in pagesInRange"
      :key="idx"
      :class="{ active: currentPage === page }"
      class="pagination-item"
      @click="changePage(page)"
    >
      {{ page }}
    </button>

    <b-icon v-show="isFirstPages" class="pagination-dots" name="dots" />
    <button
      v-show="isFirstPages"
      class="pagination-item"
      :class="{ active: currentPage === pageCount }"
      @click="last"
    >
      {{ pageCount }}
    </button>

    <b-icon
      name="arrow-next"
      class="pagination-arrow"
      :class="{ disabled: currentPage === pageCount }"
      @click="next"
    />
  </div>
</template>

<script lang="ts" setup>
import { computed } from 'vue'
import { useModelValue } from '@/composables/useModelValue'
import BIcon from '../icon/BIcon.vue'

defineEmits(['update:modelValue'])

const props = defineProps<{
  modelValue: number
  total: number
  limit: number
  scrollTo: string
}>()

const { value: currentPage, setValue } = useModelValue(props)

/**
 * Минимальное количество кнопок без скрытия
 */
const MIN_COUNT_BTN_PAGES = 5

/**
 * Количество страниц - кол-во итемов всего / кол-во итемов на странице
 */
const pageCount = computed(() => Math.ceil(props.total / props.limit))

/**
 * Отображаем ли точки слева
 */
const hasLeftDots = computed(
  () =>
    currentPage.value > MIN_COUNT_BTN_PAGES - 2 &&
    pageCount.value > MIN_COUNT_BTN_PAGES
)

/*
 * Отображаются ли первые две страницы - пока они отображраются - выводить правые точки и
 * последнюю страницу
 */
const isFirstPages = computed(
  () =>
    currentPage.value < MIN_COUNT_BTN_PAGES - 2 &&
    pageCount.value > MIN_COUNT_BTN_PAGES
)

/**
 * Остальные отображаемые кнопки
 *
 * @example
 * [ [1], 2,  3, ..., 10  ]
 * [  1, [2], 3, ..., 10  ]
 * [  1,  2, [3], 4,  5   ]
 * [  1, ..., 3, [4], 5   ]
 * [  1, ..., 4, [5], 6   ]
 * [  1, ..., 5, [6], 7   ]
 * [  1, ..., 6, [7], 8   ]
 * [  1, ..., 7, [8], 9   ]
 * [  1, ..., 8, [9], 10  ]
 * [  1, ..., 8,  9, [10] ]
 */
const pagesInRange = computed(() => {
  let rangeLeft = MIN_COUNT_BTN_PAGES - 1
  let rangeRight = MIN_COUNT_BTN_PAGES - 1

  // Страниц меньше минимального кол-ва - выводим сразу все
  if (pageCount.value > MIN_COUNT_BTN_PAGES) {
    // Если выбрана последняя кнопка - то отображаем две еще слева, иначе только одну
    rangeLeft = currentPage.value === pageCount.value ? 2 : 1

    // Если первые две страницы - не трогаем коллапс, отображаем их
    // № страницы меньше кол-ва кнопок - высчитываем, сколько кнопок надо до коллапса
    // FIXME: нужно разложить функцию на более понятную структуру!
    rangeRight =
      currentPage.value < 3
        ? 3 - currentPage.value
        : currentPage.value < MIN_COUNT_BTN_PAGES - 1
        ? MIN_COUNT_BTN_PAGES - currentPage.value
        : 1
  }

  /**
   * Ограничение, чтобы не было выхода за пределы массива
   */
  const left = Math.max(2, currentPage.value - rangeLeft)
  const right = Math.min(currentPage.value + rangeRight, pageCount.value)

  const pages = []
  for (let i = left; i <= right; i++) {
    pages.push(i)
  }

  return pages
})

/**
 * Previous button click listener.
 */
const prev = () => {
  changePage(currentPage.value - 1)
}

/**
 * Next button click listener.
 */
const next = () => {
  changePage(currentPage.value + 1)
}

/**
 * First button click listener.
 */
const first = () => {
  changePage(1)
}

/**
 * Last button click listener.
 */
const last = () => {
  changePage(pageCount.value)
}

/**
 * Обрабатываем клик смены страницы,
 * и скроллим к указанному классу
 * @param page
 */
const changePage = (page: number) => {
  if (page < 1 || page > pageCount.value) return

  scrollToSelector()

  if (page !== currentPage.value) {
    setValue(page)
  }
}

/**
 * Поднимаем страницу
 */
const scrollToSelector = () => {
  if (!props.scrollTo) return

  document.querySelector(props.scrollTo)?.scrollIntoView({
    behavior: 'smooth'
  })
}
</script>

<style lang="postcss" scoped>
.pagination {
  @apply w-full flex justify-center items-end gap-8 md:gap-12 xl:gap-16
    mt-24 md:mt-30 xl:mt-80;

  &-arrow {
    @apply cursor-pointer pointer-events-auto;
    @apply h-24 w-24 md:h-34 md:w-34 xl:h-44 xl:w-44;

    &.disabled {
      @apply pointer-events-none text-black/50 hover:text-black/50 cursor-default;
    }
  }

  &-item {
    @apply t-pagination transition text-center cursor-pointer select-none;
    @apply w-24 h-24 md:w-34 md:h-34 xl:w-44 xl:h-44 hover:text-green hover:border-green;

    &.active {
      @apply text-auxiliary-6 rounded-[4px] border-auxiliary-6 border-2 md:border-[3px];
      @apply hover:text-green hover:border-green;
    }
  }

  &-dots {
    @apply w-24 h-24 md:w-34 md:h-34 xl:w-44 xl:h-44;
  }
}
</style>
