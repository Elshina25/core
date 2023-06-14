import { PageContext } from 'renderer/types'
import { DEFAULT_OG_IMAGE } from '@/config/meta.config'
import { getFullUrl, getSiteUrl } from '@/utils'

export function getPageTitle(pageContext: PageContext): string {
  // For static titles (defined in the `export { documentProps }` of the page's `.page.js`)
  // For dynamic tiles (defined in the `export addContextProps()` of the page's `.page.server.js`)
  const { title } =
    pageContext.exports?.documentProps || pageContext.documentProps || {}

  if (title) {
    return `${title}`
  }

  return 'CORE.XP — Лидирующая консалтинговая компания в области недвижимости'
}

/**
 * Get canonical link for meta tag with rel="canonical"
 * @param pageContext Context of the page
 * @returns Canonical link of the page
 */
export function getCanonicalLink(pageContext: PageContext): string {
  return getSiteUrl(pageContext.urlParsed?.pathname ?? pageContext.urlPathname)
}

export function getPageDescription(pageContext: PageContext): string {
  // For static titles (defined in the `export { documentProps }` of the page's `.page.js`)
  // For dynamic tiles (defined in the `export addContextProps()` of the page's `.page.server.js`)
  const { description } =
    pageContext.exports?.documentProps || pageContext.documentProps || {}

  return description ?? 'Международная консалтинговая компания CORE.XP'
}

export function getPageOgImage(pageContext: PageContext): string {
  // For static titles (defined in the `export { documentProps }` of the page's `.page.js`)
  // For dynamic tiles (defined in the `export addContextProps()` of the page's `.page.server.js`)
  const { image } =
    pageContext.exports?.documentProps || pageContext.documentProps || {}

  return image
    ? getFullUrl(image)
    : import.meta.env.VITE_BASE_URL + DEFAULT_OG_IMAGE
}
