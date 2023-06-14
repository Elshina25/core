import type { ComputedRef, InjectionKey } from 'vue'

export const selectedTitleSymbol: InjectionKey<ComputedRef<string>> =
  Symbol('selected-title')
