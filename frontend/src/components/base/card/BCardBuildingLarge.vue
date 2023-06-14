<template>
  <div class="card">
    <!-- TODO Vlad: уточнить что выводить, если у объекта нет фото -->
    <b-gallery class="card__gallery" :items="pictures" :url="URL" pagination />
    <div class="card-body">
      <div class="card-body__group mb-36">
        <div>
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
          <span class="card-body__id">ID {{ building.id }}</span>
        </div>
        <!-- TODO Vlad: временно закомментил -->
        <div v-if="false" class="flex items-center">
          <b-share class="mr-28" :socials="SOCIALS_SHARE" />
          <b-favorite :id="building.id" />
        </div>
      </div>
      <a class="t-h3 link" :href="URL">
        {{ building.propertyNameRus }}
      </a>
      <div class="card-body__group mt-24">
        <div v-if="offers.length" class="mr-20">
          <b-offers
            class="mb-18"
            :offers="offers"
            :offers-url="`${URL}#offers`"
          />
          <a
            class="link t-p3"
            :href="`${URL}#offers`"
            v-html="allOffersLabel"
          ></a>
        </div>
        <div class="w-[290px]">
          <div>
            <!-- TODO Vlad: вывести поле с налоговой -->
            <!-- <span class="t-p3 mr-20"></span> -->
            <span v-if="building.buildingClass" class="t-p3 mr-20">
              Класс {{ building.buildingClass }}
            </span>
            <span class="t-p3">{{ building.county }}</span>
          </div>
          <div class="t-p3 mt-20">
            {{ building.propertyAddressRus }}
          </div>
          <station-item v-if="metro" class="mt-20" :station="metro" />
          <a
            :href="`${URL}#${OBJECT_REQUEST_FORM_ID}`"
            class="w-[291px] mt-92"
            type="button"
            variant="green"
          >
            <b-button type="button" variant="green">
              Заявка на просмотр
            </b-button>
          </a>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { computed } from 'vue'
import { ROUTE } from '@/routes'
import { numberFormatter } from '@/utils'
import { SOCIALS_SHARE } from '@/config/share.config'
import BGallery from '@/components/base/gallery/BGallery.vue'
import BBadge from '@/components/base/badge/BBadge.vue'
import BFavorite from '@/components/base/favorite/BFavorite.vue'
import BShare from '@/components/base/share/BShare.vue'
import BButton from '@/components/base/button/BButton.vue'
import BOffers from '@/components/base/offers/BOffers.vue'
import StationItem from '@/components/station/StationItem.vue'
import { OBJECT_REQUEST_FORM_ID } from '@/config/objects.config'
import { ObjectListResponse, Room } from '@/api/object/list'
import { useBuildingFields } from '@/composables/useBuildingFields'
import { getSpace } from '@/utils/filter'

const props = defineProps<{
  building: ObjectListResponse
}>()

// TODO: роутинг детальной страницы
const URL = computed<string>(() => {
  const section = ROUTE.ESTATE.slug
  const type = getSpace(props.building.propertyType, 'id')?.type
  const code = props.building.code // FIXME: Нужен slug

  return [section, type, code].join('/')
})

const { offer, space, metro } = useBuildingFields(props.building)

// Список предложений
const offers = computed<Room[]>(() =>
  props.building.rooms?.length ? props.building.rooms : []
)

// Фото для слайдера
const pictures = computed<string[]>(() =>
  props.building.pictures.map((url) => url)
)

// Надпись снизу карточки
const allOffersLabel = computed<string>(() => {
  const min = props.building.minUnitSize
    ? ` от ${numberFormatter(props.building.minUnitSize)}`
    : ''
  const max = props.building.maxUnitSize
    ? ` до ${numberFormatter(props.building.maxUnitSize)} м<sup>2</sup>`
    : ''

  return props.building.rooms?.length ? `Все объекты в аренду:${min}${max}` : ''
})
</script>

<style src="./styles/card-building-table.css" scoped></style>
