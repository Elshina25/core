<template>
  <div
    class="card"
    :class="{ 'card--large': showOffers, 'card--default': !showOffers }"
  >
    <b-building-share :id="building.id" :url="URL" />

    <a class="card__preview" :href="URL">
      <img :src="preview" />
    </a>

    <div class="card-body">
      <div class="card-body__info">
        <div class="card-body__head">
          <div class="card-body__tags">
            <b-badge
              v-if="offer"
              variant="outline-gray"
              class="mb-4 mr-6 md:mr-10"
            >
              {{ offer }}
            </b-badge>
            <b-badge
              v-if="space"
              variant="outline-gray"
              class="mb-4 mr-6 md:mr-10"
            >
              {{ space.name }}
            </b-badge>
          </div>

          <!-- TODO Vlad: вывести поле с налоговой -->
          <span
            v-if="false"
            class="card-body__caption"
            :class="{ '2xl:hidden': showOffers }"
          >
            {{ building.county }}
          </span>
        </div>

        <a class="card-body__name link" :href="URL">
          {{ building.propertyNameRus }}
        </a>

        <div class="card-body__address">{{ building.propertyAddressRus }}</div>

        <station-item
          v-if="metro"
          class="mt-6 md:mt-8 2xl:mt-12 mb-16"
          :class="{ '2xl:hidden': showOffers }"
          :station="metro"
        />
      </div>

      <div>
        <b-offers
          v-if="showOffersList"
          :offers="offers"
          :offers-url="`${URL}#offers`"
        />
        <a class="card-body__offers link" :href="`${URL}#offers`">
          <span v-html="offersLabel"></span>
        </a>
      </div>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { computed } from 'vue'
import { ROUTE } from '@/routes'
import { getFullUrl, numberFormatter, pluralize } from '@/utils'
import { getSpace } from '@/utils/filter'
import BBadge from '@/components/base/badge/BBadge.vue'
import StationItem from '@/components/station/StationItem.vue'
import BOffers from '@/components/base/offers/BOffers.vue'
import { ObjectListResponse, Room } from '@/api/object/list'
import { useBuildingFields } from '@/composables/useBuildingFields'
import { FILTER_SPACES_DECLINATIONS } from '@/config/filter/filter.config'
import BBuildingShare from '@/components/base/share/BBuildingShare.vue'

const props = defineProps<{
  building: ObjectListResponse
  showOffers?: boolean
}>()

// TODO: роутинг детальной страницы
const URL = computed<string>(() => {
  const section = ROUTE.ESTATE.slug
  const type = getSpace(props.building.propertyType, 'id')?.type
  const code = props.building.code

  return [section, type, code].join('/')
})

const showOffersList = computed<boolean>(
  () => !!(props.showOffers && props.building.rooms?.length)
)

const { offer, space, metro } = useBuildingFields(props.building)

// Список предложений
const offers = computed<Room[]>(() => props.building.rooms?.slice(0, 3) ?? [])

// Фото
const preview = computed<string>(() =>
  props.building.pictures.length ? getFullUrl(props.building.pictures[0]) : ''
)

// Cклонения типа объекта
const declinations = computed<string[]>(() => {
  const defaultDeclinations = ['объект', 'объекта', 'объектов']
  return space.value
    ? FILTER_SPACES_DECLINATIONS[space.value.type]
    : defaultDeclinations
})

// Надпись снизу карточки (кол-во свободных объектов)
const offersLabel = computed<string>(() => {
  const minLabel = props.building.minUnitSize
    ? ` от ${numberFormatter(props.building.minUnitSize)}`
    : ''
  const maxLabel = props.building.maxUnitSize
    ? ` до ${numberFormatter(props.building.maxUnitSize)} м<sup>2</sup>`
    : ''
  const activeOffersCountLabel = pluralize(
    props.building.roomCount,
    declinations.value
  )

  return props.building.roomCount
    ? `Свободно ${activeOffersCountLabel}${minLabel}${maxLabel}`
    : ''
})
</script>

<style src="./styles/card-building.css" scoped></style>
