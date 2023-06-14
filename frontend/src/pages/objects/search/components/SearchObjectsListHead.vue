<template>
  <div class="flex items-center justify-between">
    <div class="flex">
      <b-select
        v-model="sortingValue"
        class="sorting-select"
        :options="sortingOptions"
      />
      <div class="types">
        <label
          v-for="item in types"
          :key="item.id"
          class="types__item"
          :class="{ 'types__item--active': item.id === typeValue }"
        >
          <input
            v-model="typeValue"
            :value="item.id"
            name="type"
            type="radio"
          />
          {{ item.label }}
          <b-icon v-if="item.icon" class="ml-10" :name="item.icon" />
        </label>
      </div>
    </div>
    <b-share>
      <template #label>
        <span class="mobile:!hidden link link-button t-p3">
          Поделиться подборкой
        </span>
        <span class="md:!hidden link link-button t-p2"> Поделиться </span>
      </template>
    </b-share>
  </div>
</template>

<script lang="ts" setup>
import BSelect from '@/components/base/select/BSelect.vue'
import BIcon from '@/components/base/icon/BIcon.vue'
import BShare from '@/components/base/share/BShare.vue'

import { ISelectOption } from '@/types/ui'
import { computed } from 'vue'

const emit = defineEmits<{
  (e: 'update:type', event: string): void
  (e: 'update:sort', event: string): void
}>()

const props = defineProps<{
  type: string
  sort: string
  sortingOptions: ISelectOption[]
}>()

const types: Record<string, string>[] = [
  {
    id: 'table',
    label: 'Таблицей',
    icon: 'windows'
  },
  {
    id: 'list',
    label: 'Списком',
    icon: 'burger'
  }
]

const sortingValue = computed({
  get() {
    return props.sort
  },

  set(val) {
    emit('update:sort', val)
  }
})

const typeValue = computed({
  get() {
    return props.type
  },

  set(val) {
    emit('update:type', val)
  }
})
</script>

<style lang="postcss" scoped>
.sorting-select {
  :deep(.multiselect__single) {
    @apply mobile:max-w-[110px];
  }
}

.types {
  @apply hidden xl:flex xl:items-center ml-40;

  &__item {
    @apply flex items-center t-p3 cursor-pointer mr-40;

    &--active {
      @apply opacity-50 cursor-default;
    }

    input {
      display: none;
    }
  }
}
</style>
