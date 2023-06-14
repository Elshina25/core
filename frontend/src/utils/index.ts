import { inject, InjectionKey } from 'vue'

export const injectStrict = <T>(key: InjectionKey<T>, fallback?: T) => {
  const resolved = inject(key, fallback)
  if (!resolved) {
    throw new Error(`Could not resolve ${key.description}`)
  }
  return resolved
}

/**
 * Скролл к элементу
 * @param selector
 */
export const scrollTo = (
  selector: string,
  options: Record<string, unknown> = { behavior: 'smooth' }
) => {
  document.querySelector(selector)?.scrollIntoView(options)
}

/**
 * Форматирование числа
 * @param number - значение которое необходимо отформатировать
 * @param options - настройки форматирования
 */
export const numberFormatter = (
  number: number,
  options: Record<string, unknown> = {}
): string => {
  return Intl.NumberFormat('ru', options).format(number)
}

/**
 * Конвертирование даты в строку
 * @param date
 */
export const convertDateToString = (date: Date): string => {
  return date
    .toLocaleString('en-us', {
      year: 'numeric',
      month: '2-digit',
      day: '2-digit'
    })
    .replace(/(\d+)\/(\d+)\/(\d+)/, '$3-$1-$2')
}

/**
 * Конвертирование даты по формату месяц год
 * @param date строка даты в формате HH.MM.YYYY
 * @param options формат даты, дефолтный формат - DD Month YYYY
 */
export const dateFormatter = (
  date: string | undefined | null,
  options: Intl.DateTimeFormatOptions | undefined = undefined
): string => {
  if (!date) return ''

  const defaultOptions: Intl.DateTimeFormatOptions = {
    year: 'numeric',
    month: 'long',
    day: '2-digit'
  }
  return convertStringToDate(date)
    .toLocaleString('ru-RU', options || defaultOptions)
    .replace('г.', '')
}

/**
 * Конвертирование даты по формату месяц год
 * @param date
 */
export const convertDateToMonthYear = (date: Date): string => {
  return date
    .toLocaleString('ru-RU', {
      year: 'numeric',
      month: 'long'
    })
    .replace('г.', '')
}

/**
 * Конвертирование строки даты в объект даты
 * @param date Дата в формате DD.MM.YYYY
 */
export const convertStringToDate = (date: string): Date => {
  const [day, month, year] = date.split('.')
  return new Date(+year, +month - 1, +day)
}

/**
 * Форматирование номера телефона
 * @param phone +71234567890
 * @return +7 123 456-78-90
 */
export const phoneFormatter = (phone: string): string => {
  return phone
    .replace(/\D/g, '')
    .replace(/(\d{1})(\d{3})(\d{3})(\d{2})(\d{2})/, '+7 $2 $3-$4-$5')
}

/**
 * Форматирование номера телефона для ссылки
 * @param phone +7 123 456-78-90
 * @param prefix tel:
 * @return tel:+71234567890
 */
export const phoneLinkFormatter = (phone: string, prefix = 'tel:'): string => {
  return `${prefix}+${phone.replace(/[\D]/g, '')}`
}

/**
 * Форматирование почты для ссылки
 * @param email test@test.ru
 * @param prefix mailto:
 * @return mailto:test@test.ru
 */
export const emailLinkFormatter = (
  email: string,
  prefix = 'mailto:'
): string => {
  return prefix + email
}

/**
 * Первая буква в верхнем регистре
 * @param string
 */
export const upperCaseFirstLetter = (string: string): string => {
  return string.charAt(0).toUpperCase() + string.slice(1)
}

/**
 * Первая буква в нижнеем регистре
 * @param string
 */
export const lowerCaseFirstLetter = (string: string): string => {
  return string.charAt(0).toLowerCase() + string.slice(1)
}

/**
 * Принимает относительный путь к файлу, возвращает абсолютный URL
 * @param path относительный путь к файлу
 */
export const getFullUrl = (path: string): string => {
  if (!path) return ''

  if (!path.startsWith('/')) {
    path = `/${path}`
  }

  return new URL(import.meta.env.VITE_API_URL + path).toString()
}

/**
 * Вытаскиваем первый набор цифр для красивого отображения
 * @param title
 */
export const convertFactTitle = (title: string) => {
  return title.replace(/([?:\W])?([\d ,.]*)([?:\W])?/, `$1<span>$2</span>$3`)
}

/**
 * Принимает относительный путь к файлу, возвращает абсолютный URL (относительно URL'а сайта)
 * @param path относительный путь к файлу
 */
export const getSiteUrl = (path: string): string => {
  if (!path) return ''

  if (!path.startsWith('/')) {
    path = '/' + path
  }

  return import.meta.env.VITE_BASE_URL + path
}

/**
 * Обрезание текста с добавлением троеточия
 * @param string
 * @param max
 * @returns string | str...
 */
export const truncate = (string: string, max: number): string => {
  return string.length > max ? string.slice(0, max - 1) + '…' : string
}

/**
 * Функция склонения слов
 * Пример использования: pluralize(1, ['символ', 'символа', 'символов'])
 * @param count
 * @param words
 * @param concatenate
 */
export const pluralize = (
  count: number,
  words: string[],
  concatenate = true
): string => {
  const text =
    words[
      count % 10 === 1 && count % 100 !== 11
        ? 0
        : count % 10 >= 2 &&
          count % 10 <= 4 &&
          (count % 100 < 10 || count % 100 >= 20)
        ? 1
        : 2
    ]

  return concatenate ? `${count} ${text}` : text
}

/**
 * Получение объекта с гет параметрами
 * @param params - window.location.search, pageContext.urlParsed.searchOriginal
 * @returns object
 */
export const getConvertedQueryParams = <T = Record<string, string | string[]>>(
  query: string
): T => {
  if (!query) return {} as T

  const convertedParams: Record<string, string | string[]> = {}

  new URLSearchParams(query).forEach((value, key) => {
    // Ключ параметра
    let decodedKey = decodeURIComponent(key)
    // Значение параметра
    const decodedValue = decodeURIComponent(value)

    if (decodedKey.endsWith('[]')) {
      // Если массив, конвертируем
      decodedKey = decodedKey.replace('[]', '')
      // Инициализируем массив
      convertedParams[decodedKey] || (convertedParams[decodedKey] = [])
      // @ts-ignore Добавляем значение в массив
      convertedParams[decodedKey].push(decodedValue)
    } else {
      // Если строка, добавляем в объект ключ с значением
      convertedParams[decodedKey] = decodedValue
    }
  })

  return convertedParams as T
}

/**
 * Конвертирование объекта в URLSearchParams
 * @param params
 * @param defaultParams
 * @returns URLSearchParams
 */
export const convertObjectToURLSearchParams = (
  params: Record<string, unknown>,
  defaultParams: Record<string, string> | string = {}
): URLSearchParams => {
  const queryParams = new URLSearchParams(defaultParams)
  // Собираем get параметры
  for (const [key, value] of Object.entries(params)) {
    if (!value || (Array.isArray(value) && !value.length)) continue
    // Если массив, то конвертируем его в key[] = value
    if (Array.isArray(value)) {
      value.forEach((val) => queryParams.append(`${key}[]`, '' + val))
      continue
    }
    queryParams.set(key, '' + value)
  }

  return queryParams
}

/**
 * Добавление get-параметров к URL
 * @param params
 * @param defaultParams
 * @returns
 */
export const setQueryParams = (
  params: Record<string, unknown>,
  defaultParams: Record<string, string> | string = {}
) => {
  if (!location) return

  const queryParams = convertObjectToURLSearchParams(params, defaultParams)

  window.history.replaceState({}, '', `${location.pathname}?${queryParams}`)
}
