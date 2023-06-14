import type { ComputedRef, InjectionKey } from 'vue'

export const loadingSymbol: InjectionKey<ComputedRef<boolean>> =
  Symbol('loading')
