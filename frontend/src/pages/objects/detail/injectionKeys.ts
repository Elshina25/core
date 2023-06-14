import { ComputedRef, InjectionKey } from 'vue'
import { PageProps } from '@/pages/objects/detail/types'

export const KEY_OBJECT: InjectionKey<ComputedRef<PageProps['object']>> =
  Symbol('object-detail')

export const KEY_SHARE: InjectionKey<string[]> = Symbol('share')
