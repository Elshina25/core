import { reactive } from 'vue'

/**
 * Управление модальными окнами
 * @param variantDefault
 * @returns
 */
export const useModal = () => {
  const modal = reactive({
    status: false,
    open: () => (modal.status = true),
    close: () => (modal.status = false)
  })

  return modal
}
