import api from '@/api'
import { FEEDBACK_TOPICS_WITH_MORE } from '@/config/form.config'

export const getFeedbackTopics = async () => {
  try {
    const { data } = await api.form.feedback.topics()

    return data.interest
      .map((item) => {
        // Добавление подкатегорий в определенные категории
        if (FEEDBACK_TOPICS_WITH_MORE.includes(item.code)) {
          // TODO: Нормализовать сортировку
          item.more = data.type
            .map((item) => {
              switch (item.code) {
                case 'office':
                  item.sort = 1000
                  break
                case 'retail':
                  item.sort = 900
                  break
                case 'industrial':
                  item.sort = 800
                  break
                case 'land':
                  item.sort = 700
                  break
                case 'other':
                  item.sort = 0
                  break
                default:
                  item.sort = 10
              }
              return item
            })
            .sort((a, b) => b.sort - a.sort)
        }

        // TODO: Нормализовать сортировку
        switch (item.code) {
          case 'rent':
            item.sort = 1000
            break
          case 'sell':
            item.sort = 900
            break
          case 'buy':
            item.sort = 800
            break
          case 'object_valuation':
            item.sort = 700
            break
          case 'other':
            item.sort = 0
            break
          default:
            item.sort = 10
        }

        return item
      })
      .sort((a, b) => b.sort - a.sort)
  } catch (err) {
    console.error('Ошибка получения тем для формы "Связаться с нами"', err)

    return []
  }
}
