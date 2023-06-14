import type { InjectionKey } from 'vue'
import { PageProps } from '@/pages/projects/list/types'

export const KEY_PARAMS: InjectionKey<PageProps['queryParams']> =
  Symbol('params')

export const KEY_CATEGORIES: InjectionKey<PageProps['categories']> =
  Symbol('categories')
