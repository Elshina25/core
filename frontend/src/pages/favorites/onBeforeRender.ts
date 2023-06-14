import type { IBeforeRenderReturn } from '@/types/renderer'

export async function onBeforeRender(): Promise<IBeforeRenderReturn> {
  return {
    pageContext: {
      pageProps: {
        showMorePage: 'about'
      },
      documentProps: {
        title:
          'Избранное | CORE.XP — Лидирующая консалтинговая компания в области недвижимости',
        description:
          'CORE.XP: аренда и продажа офисов в Москве, аренда и продажа складов в Московской области, аренда в торговых центрах, аренда и продажа в регионах, объекты для инвестирования'
      }
    }
  }
}
