import { Ref } from 'vue'

export interface IForm {
  values: Record<string, unknown>
  rules: Record<string, string>
  errors: Record<string, string>
  key: Ref<string>
}

export interface ISelectOption {
  id: string
  name: string
}

export interface ISelectTag {
  name: string
  code: string
  sort: number
}

export type ISelectValue = ISelectOption['id']

export interface IDropdownItem {
  label: string
  value: string
  icon?: string
  collection?: string
  isTextLink?: boolean
}

// TODO: нужен рефакторинг
export interface IFilterAreaValue {
  id: string
  name: string
  slug?: string
}

export interface IFilterArea {
  id: string
  label: string
  values: IFilterAreaValue[]
  selected: Array<string | number>
  count: string | number
}

export interface IMap {
  geoObjects: {
    events: {
      add: (
        arg0: string,
        arg1: (e: { get: (arg0: string) => number }) => Promise<void>
      ) => void
    }
  }
}

export type CheckboxValue = string | number | boolean | null

export type CheckboxVariant = 'button' | 'default' | 'inline'

export type RadioValue = string | number | boolean | null
