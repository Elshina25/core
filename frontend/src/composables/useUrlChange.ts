import { watch } from 'vue'
import { PageContextServer } from '~/renderer/types'

interface UrlChangeParams {
  callback: () => void
  pageContext: PageContextServer
}

/**
 * Trigger callback after URL has changed
 * @param callback - Callback to execute **after** URL has changed
 * @param pageContext - Context of a page (Server side)
 */
export const useUrlChange = ({ callback, pageContext }: UrlChangeParams) => {
  watch(() => pageContext.routeParams, callback, {
    flush: 'post',
    immediate: true
  })
}
