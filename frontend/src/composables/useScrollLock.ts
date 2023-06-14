import { Ref } from 'vue'

/**
 * Добавляемый класс
 */
const CLASSNAME = 'scroll-lock'

/**
 * Блокировка скроллинга
 */
export const useScrollLock = (is: boolean, el: Ref<HTMLDivElement>) => {
  // Получаем размер скроллбара
  const scrollWidth = window.innerWidth - document.body.clientWidth
  const paddingRight = is ? `${scrollWidth}px` : ''

  // Блокируем скроллинг
  document.body.classList.toggle(CLASSNAME, is)

  // Добавляем отступ документы (вместо размера скроллбара)
  document.body.style.paddingRight = paddingRight

  // Добавляем отступ нашему элементы
  el.value.style.marginRight = paddingRight

  return paddingRight
}
