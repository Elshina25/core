export type SearchTypes = 'object' | 'info'

export const LIMIT_DEFAULT = 6
export const LIMITS: Record<SearchTypes, number> = {
  object: 6,
  info: 10
}
