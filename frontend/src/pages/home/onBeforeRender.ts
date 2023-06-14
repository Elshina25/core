import type { IBeforeRenderReturn } from '@/types/renderer'

export async function onBeforeRender(): Promise<IBeforeRenderReturn> {
  return {
    pageContext: {
      pageProps: {
        showMorePage: 'about'
      },
      documentProps: {
        title:
          'CORE.XP — Лидирующая консалтинговая компания в области недвижимости',
        description: 'Международная консалтинговая компания CORE.XP'
      }
    }
  }
}
