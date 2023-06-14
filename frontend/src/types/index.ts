export interface IResearch {
  id: string
  name: string
  slug?: string
  section: ITag['name'] | null
  type: ITag['name'] | null
  year: ITag['name'] | null
  date: string
  image: string
  closed: number
}

export interface IFactoid {
  tag?: string
  name: string
  previewText: string
}

export interface IAward {
  id: string
  year: number
  name: string
  previewText: string
  tags: string[]
}

export interface ITag<T = string | number> {
  name: string
  code: T
}

export interface INews {
  name: string
  id: number
  slug: string
  image: string
  date: string
  topic: string[]
  preview: string
}

export interface IShare {
  id: string
  link: string
  label: string
  icon?: string
  collection?: string
}

export interface IStatistic {
  label: string
  value: string
}

export interface IMapCoord {
  latitude: number
  longitude: number
}

export interface IMap {
  coords: IMapCoord[]
  zoom?: number
}

export interface IPerson {
  name: string
  image: string
  jobTitle?: string
  phone?: string
  email?: string
  telegram?: string
  viber?: string
  whatsapp?: string
}

export interface IPersonTeam extends IPerson {
  previewText: string
  imageDetail: string
  yearExperience: string
  experienceHistory?: string
}

export interface IResearchFactoid {
  description: string
  title: string
  subTitle: string
  arrow: 'up' | 'down' | 'none'
}

export interface IFeedbackTopic {
  code: string
  name: string
  sort: number
  more?: Omit<IFeedbackTopic, 'more'>[]
}

// TODO: remove
export interface IFastSearch {
  id: number | string
  name: string
  path: string
  params?: Record<string, unknown>
}

export interface IObjectTab {
  id: number | string
  name: string
  title: string
  values: IFastSearch[]
}

export interface IServiceItem {
  id: number
  code: string
  name: string
  iblockSectionId: number
  fastLink: string | false
  date: string
}

export interface IServiceGroup {
  id: number
  code: string
  name: string
  picture: string | null
  description?: string | null
  items?: IServiceItem[]
}

export interface IProject {
  id: number
  preview: string
  name: string
  slug: string
  section: ITag['name'] | null
  type: ITag['name'] | null
  date: string
  image: string
}

export interface IFormLabels {
  title: string
  description: string
}

export interface ISeo {
  elementMetaKeywords: string
  elementMetaDescription: string
  elementMetaTitle: string
}

export interface IClient {
  id: string
  name: string
  picture: string
}

export interface IProjectSlide {
  name: string
  preview: string
  companyName: string
  facts: IProjectSlideFact[]
  section: ITag<string>
  type: ITag<string>
  image: string
  slug: string
}

export interface IProjectSlideFact {
  title: string
  description: string
}

export interface IBlog {
  picture: string
  immovablesValue: string
  offersValue: string
  code: string
  name: string
  previewText: string
  activeFrom: string
}

export interface IStory {
  author: IPerson
  previewText: string
  topics: string[]
}

export interface IVacancy {
  name: number
  id: number
  link: string
  city: string
  metro: string
  salaryFrom: number
  salaryTo: number
}

export interface IWhatNewCard {
  slug: string
  image?: string
  name: string
  date: Date
  type: 'blog' | 'research' | 'news' | 'service'
}

export interface IOffer {
  name: string
  previewText: string
}
