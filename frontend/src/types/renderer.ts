import { IMorePageKey } from '@/config/morePage.config'

export interface DefaultPageProps {
  showMorePage: IMorePageKey
}

export interface IPageContextMeta {
  title?: string
  description?: string
  image?: string
}

export type IBeforeRenderReturn<T = DefaultPageProps> = {
  pageContext: {
    pageProps: T & DefaultPageProps
    documentProps?: IPageContextMeta
  }
}
