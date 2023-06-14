import type { IFilterFieldsStore } from '@/types/filter'
import { useFilterAddressStore } from '@/stores/filter/address'
import { useFilterCityStore } from '@/stores/filter/cities'
import { useFilterClassStore } from '@/stores/filter/classes'
import { useFilterCountieStore } from '@/stores/filter/counties'
import { useFilterDistrictStore } from '@/stores/filter/districts'
import { useFilterFloorStore } from '@/stores/filter/floor'
import { useFilterMetroStore } from '@/stores/filter/metros'
import { useFilterOfferStore } from '@/stores/filter/offers'
import { useFilterParkingStore } from '@/stores/filter/parking'
import { useFilterPriceStore } from '@/stores/filter/price'
import { useFilterSpaceStore } from '@/stores/filter/spaces'
import { useFilterSquareStore } from '@/stores/filter/square'
import { useSortStore } from '@/stores/sort'
import { useFilterSearchStore } from '@/stores/filter/search'
import { useFilterDirectionsStore } from '@/stores/filter/directions'
import { useFilterDistanceFromCity } from '@/stores/filter/distanceFromCity'

/**
 * Список store-ов с фильтрами
 */
export const useFilterStore = () => {
  const storeFilters: IFilterFieldsStore = {
    address: useFilterAddressStore(),
    city: useFilterCityStore(),
    buildingClass: useFilterClassStore(),
    county: useFilterCountieStore(),
    district: useFilterDistrictStore(),
    floor: useFilterFloorStore(),
    metro: useFilterMetroStore(),
    offer: useFilterOfferStore(),
    parking: useFilterParkingStore(),
    price: useFilterPriceStore(),
    space: useFilterSpaceStore(),
    square: useFilterSquareStore(),
    sort: useSortStore(),
    search: useFilterSearchStore(),
    distanceFromCity: useFilterDistanceFromCity(),
    directions: useFilterDirectionsStore()
  }

  return { storeFilters }
}
