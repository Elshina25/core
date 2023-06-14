import { computed, onMounted, ref, Ref } from 'vue'

//Функция создает объект нативного наблюдателя, и передает ему коллбэк
const newResizeObserver = (callback: ResizeObserverCallback) => {
  // Skip this feature for browsers which
  // do not support ResizeObserver.
  // https://caniuse.com/#search=resizeobserver
  if (typeof ResizeObserver === 'undefined') return

  return new ResizeObserver(callback)
}

const INACCURACY = 10 // Погрешность скроллинга

export const useShadowScroll = (
  scrollContainer: Ref<HTMLDivElement | null>
) => {
  const isRightActive = ref(false)
  const isLeftActive = ref(false)
  let SCROLL_MARGIN = 150

  onMounted(() => {
    SCROLL_MARGIN = scrollContainer.value
      ? scrollContainer.value.scrollWidth / 10
      : 150

    getShadows()

    // Наблюдатель на хуке ресайза - нужны ли тени после ресайза
    const scrollContainerObserver = newResizeObserver(getShadows)

    if (scrollContainerObserver && scrollContainer.value) {
      scrollContainerObserver.observe(scrollContainer.value)
    }
  })

  /**
   * Строка с классами, применяемая в компоненте
   */
  const shadowClasses = computed(() => {
    return `${isRightActive.value ? 'shadow-right' : ''} ${
      isLeftActive.value ? 'shadow-left' : ''
    }`
  })

  /**
   * Метод актуализирует статус теней у скролла
   */
  const getShadows = () => {
    if (!scrollContainer.value) return

    const hasHorizontalScrollbar =
      scrollContainer.value.clientWidth < scrollContainer.value.scrollWidth

    // Проскроллен ли элемент полностью влево
    const scrolledToLeft = scrollContainer.value.scrollLeft < INACCURACY

    // Сколько проскроллено слева - для проверки правой тени
    const scrolledFromLeft =
      scrollContainer.value.offsetWidth + scrollContainer.value.scrollLeft

    // Проскроллен ли элемент полностью вправо
    const scrolledToRight =
      scrolledFromLeft >= scrollContainer.value.scrollWidth - INACCURACY

    isRightActive.value = hasHorizontalScrollbar && !scrolledToRight
    isLeftActive.value = hasHorizontalScrollbar && !scrolledToLeft
  }

  /**
   * Скроллим влево
   */
  const scrollLeft = () => {
    if (!scrollContainer.value) return
    scrollContainer.value.scrollLeft -= SCROLL_MARGIN
  }

  /**
   * Скроллим вправо
   */
  const scrollRight = () => {
    if (!scrollContainer.value) return
    scrollContainer.value.scrollLeft += SCROLL_MARGIN
  }

  return {
    shadowClasses,
    scrollLeft,
    scrollRight,
    scrollHandler: () => {
      getShadows()
    }
  }
}
