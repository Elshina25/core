import pinia from '@/stores'
import { setActivePinia } from 'pinia'
import { PageContext } from '~/renderer/types'

/**
 * Принудительная инициализация стора, так как onBeforeRender страницы
 * отрабатывает раньше server и renderer
 * @see https://github.com/vuejs/pinia/discussions/1238
 * @param context
 */
export const initStore = (context: PageContext) => {
  const store = pinia()

  setActivePinia(store)

  context.initialStoreState = store.state.value
}
