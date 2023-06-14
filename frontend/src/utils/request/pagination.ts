export type HasPaginationParams<T> = {
  page: number
} & Omit<T, 'offset' | 'limit'>

/**
 * Добавление параметров пагинации для запроса,
 * в котором есть offset и limit
 */
export const handlePaginationParams = (page = 1, limit = 8) => {
  const currentPage = page > 0 ? page - 1 : 0
  const offset = currentPage * limit

  return { offset, limit }
}
