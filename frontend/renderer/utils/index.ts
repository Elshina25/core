// `usePageContext` allows us to access `pageContext` in any Vue component.
// See https://vite-plugin-ssr.com/pageContext-anywhere
import { inject } from 'vue'
import type { App, InjectionKey } from 'vue'
import { PageContext } from '../types'
import { DefaultPageProps } from '@/types/renderer'
import { getConvertedQueryParams } from '@/utils'

const key: InjectionKey<PageContext> = Symbol()

/**
 * Получение контекста
 * @returns
 */
export function usePageContext() {
  const pageContext = inject(key)
  if (!pageContext) throw new Error('setPageContext() not called in parent')
  return pageContext
}

/**
 * Получение параметров страницы
 * @returns
 */
export function getPageProps<T = DefaultPageProps>() {
  const pageContext = usePageContext()
  return pageContext.pageProps as T & DefaultPageProps
}

/**
 * Получение get параметров
 * @returns
 */
export function getQueryParams<T = Record<string, string | string[]>>(): T {
  const pageContext = usePageContext()
  return getConvertedQueryParams(pageContext.urlParsed.searchOriginal || '')
}

/**
 * Установка контекста
 * @param app
 * @param pageContext
 */
export function setPageContext(app: App, pageContext: PageContext) {
  app.provide(key, pageContext)
}

/**
 * Проверка активной страницы
 * @param slug
 * @returns
 */
export const isActiveLink = (slug: string) => {
  const pageContext = usePageContext()
  return pageContext.urlPathname === slug
}
