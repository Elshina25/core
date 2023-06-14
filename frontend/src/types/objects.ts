export interface IStation {
  code: string
  name: string
  distance: number
  color: string
}

export interface IObjectFactoid {
  value: string
  title: string
  subtitle?: string
}

export interface IObjectTableItem {
  crmId: string
  type: string
  condition: string
  size: number
  floorNumber: number
}

export interface IObjectSearchResponse {
  name: string
  id?: number
  code: string
}

export interface IObjectSearch extends IObjectSearchResponse {
  collection: string
  icon: string
  prefix?: string
}
