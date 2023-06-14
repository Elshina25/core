import { InjectionKey } from 'vue'

/**
 * Прокидываемые свойства в дочеркие компоненты модалок
 * Доступны для управления снизу-вверх
 */
interface IModal {
  close: () => void
}

export const ModalSymbol: InjectionKey<IModal> = Symbol('modal')
