<template>
  <div v-if="tableItems?.length" class="table">
    <b-tags
      v-model="filter"
      class="mb-12 md:mb-20 xl:mb-36"
      :padding-container="false"
      :items="filters"
    />

    <div class="table__header">
      <div
        v-for="(header, idx) in headers"
        :key="idx"
        class="table__header-cell"
        :class="`table__header-cell--${header.class}`"
      >
        {{ header.label }}
      </div>
    </div>

    <div class="flex flex-col gap-8 md:gap-10">
      <object-table-item
        v-for="(row, idx) in tableItems"
        :key="idx"
        :item="row"
        @request-price="handleRequestPrice"
      />
    </div>
    <b-modal v-model="modal.status" background container>
      <form-request-price :crm-id="tableItemId" />
    </b-modal>
  </div>
</template>

<script lang="ts" setup>
import { computed, ComputedRef, Ref, ref } from 'vue'
import { ITag } from '@/types'
import { injectStrict } from '@/utils'
import { KEY_OBJECT } from '../injectionKeys'
import { IObjectTableItem } from '@/types/objects'
import BModal from '@/components/base/modal/BModal.vue'
import { useModal } from '@/composables/useModal'
import BTags from '@/components/base/tags/BTags.vue'
import ObjectTableItem from '@/components/table/ObjectTableItem.vue'
import FormRequestPrice from '@/components/form/object/FormRequestPrice.vue'

const tableItemId: Ref<string> = ref('')
const modal = useModal()
const object = injectStrict(KEY_OBJECT)

const filters: ComputedRef<ITag<string>[]> = computed(() => {
  const filters = []

  if (object.value.rooms.rent.length) {
    filters.push({ name: 'Аренда', code: 'rentArea' })
  }

  if (object.value.rooms.sale.length) {
    filters.push({ name: 'Продажа', code: 'sellArea' })
  }

  return filters
})

const filter: Ref<string> = ref(
  filters.value.length ? filters.value[0].code : ''
)

const headers = [
  { label: 'Назначение', class: 'purpose' },
  { label: 'Состояние', class: 'condition' },
  { label: 'Площадь', class: 'size' },
  { label: 'Этаж', class: 'floor' },
  { label: 'Цена', class: 'price-btn' }
]

const tableItems: ComputedRef<IObjectTableItem[]> = computed(() => {
  switch (filter.value) {
    case 'rentArea':
      return object.value.rooms.rent
    case 'sellArea':
      return object.value.rooms.sale
    default:
      return []
  }
})

const handleRequestPrice = (itemId: IObjectTableItem['crmId']) => {
  tableItemId.value = itemId
  if (tableItemId.value) {
    modal.open()
  }
}
</script>

<style scoped lang="postcss">
.table {
  @apply w-full mt-40 xl:mt-80;

  &__header {
    @apply ml-20 mb-10 xl:ml-30 leading-140 hidden xl:flex;

    &-cell {
      @apply t-button text-black/60 font-semibold;

      &--purpose {
        @apply w-[50%];
      }

      &--condition,
      &--size,
      &--floor {
        @apply w-[15%];
      }

      &--price-btn {
        @apply w-[20%];
      }
    }
  }
}
</style>
