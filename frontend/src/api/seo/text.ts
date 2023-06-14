import { requestPromise } from '@/utils/request'
import {IFactoid} from "@/types";

/**
 * Получение SEO-тэга по значению адреса страницы
 * @returns
 */
export default async (url: string) => {
  try {
    return await requestPromise<SeoTextResponse, SeoTextRequest>({
      url: `seo/text/`,
      method: 'get',
      params: { url }
    })
  } catch (err) {
    console.error('Ошибка получения списка SEO-данных', err)
    return []
  }
}

export interface SeoTextRequest {
  url: string
}

/**
 * Структура ответа
 */
export interface SeoTextResponse {
  url: string,
  text: string,
}