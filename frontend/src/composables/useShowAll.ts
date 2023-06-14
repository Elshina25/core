import { computed, ref } from 'vue'

/**
 * Отображение всего списка
 * @param count
 * @param max
 * @returns
 */
export const useShowAll = (count: number, max = 8) => {
  /**
   * Раскрыть список (показать все)
   */
  const showAll = ref(false)

  /**
   * Отображаем ли кнопку показать все
   */
  const showBtnAll = computed(() => count > max)

  /**
   * Отображаем или скрываем ссылку
   * @param idx
   */
  const isShow = (idx: number) => {
    // Если список скрыт, то отображаем только первые элементы
    if (!showAll.value) {
      return idx < max
    }

    return true
  }

  return {
    showAll,
    showBtnAll,
    isShow
  }
}
