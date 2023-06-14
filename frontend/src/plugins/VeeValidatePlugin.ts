import { defineRule, configure } from 'vee-validate'
import { required, email, min, max, confirmed } from '@vee-validate/rules'
import { PHONE_MASKA } from '@/config/form.config'

export default (): void => {
  defineRule('required', required)
  defineRule('email', email)
  defineRule('min', min)
  defineRule('max', max)
  defineRule('confirmed', confirmed)
  defineRule('phone', (value: string) => value.length === PHONE_MASKA.length)

  // Прописываем ошибки нашим правилам
  configure({
    generateMessage: (context) => {
      switch (context.rule?.name) {
        /**
         * Обязательное поле
         */
        case 'required':
          return 'Обязательное поле'

        /**
         * Электронный адрес
         */
        case 'email':
          return 'Электронный адрес указан некорректно'

        /**
         * Номер телефона
         */
        case 'phone':
          return 'Номер телефона указан некорректно'

        /**
         * Повтор пароля
         */
        case 'confirmed':
          return 'Пароли не совпадают'

        /**
         * Минимальная длина
         */
        case 'min': {
          if (Array.isArray(context.rule.params)) {
            const [min] = context.rule.params
            return `Минимальная длина ${min} символов`
          }

          break
        }

        /**
         * Максимальная длина
         */
        case 'max': {
          if (Array.isArray(context.rule.params)) {
            const [max] = context.rule.params
            return `Максимальная длина ${max} символов`
          }

          break
        }
      }

      /**
       * Необработанна ошибка (исключение)
       */
      return 'Ошибка заполнения поля'
    }
  })
}
