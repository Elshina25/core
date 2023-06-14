import api from '@/api'

/**
 * Получение SEO-текста
 */
export const getSeoText = async (context: PageContext) => {
  const pathname = context.urlPathname
  const url = pathname.endsWith('/') ? pathname.slice(0, -1) : pathname

  try {
    const res = await api.seo.text(url);
    return res.data ? res.data?.text ?? '' : ''
  } catch (err) {
    console.error('Ошибка получения SEO-текста', err)

    return {
      objects: [],
      count: 0,
      offset: '0',
      limit: '0'
    }
  }
}
