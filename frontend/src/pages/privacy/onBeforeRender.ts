import { IBeforeRenderReturn } from '@/types/renderer'

export async function onBeforeRender(): Promise<IBeforeRenderReturn> {
  return {
    pageContext: {
      documentProps: {
        title: 'Политика конфиденциальности'
      },
      pageProps: {
        showMorePage: 'about'
      }
    }
  }
}
