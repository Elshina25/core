<template>
  <Multiselect
    v-if="client"
    v-model="selected"
    :placeholder="placeholder"
    :options="options"
    :track-by="trackBy"
    :label="label"
    :searchable="searchable"
    :multiple="multiple"
    :close-on-select="closeOnSelect"
    :show-labels="showLabels"
    :allow-empty="allowEmpty"
  >
    <template #caret>
      <b-icon class="multiselect__caret" name="arrow-down" />
    </template>
    <template #noOptions>Загрузка данных...</template>
    <template #noResult>Ничего не найдено</template>
    <template #option="{ option }">
      <filter-checkbox
        v-if="multiple"
        :checked="isChecked(option.id)"
        :label="option.name"
        disabled
      />
      <filter-radio
        v-else-if="radio && !Array.isArray(modelValue)"
        :value="option.id"
        :model-value="modelValue"
        :label="option.name"
      />
      <span v-else>{{ option.name }}</span>
    </template>
    <template #singleLabel="{ option }">
      {{ option.name }}
    </template>
    <template #selection="{ values }">
      <span v-if="values.length" class="multiselect__value">
        {{ values[0].name }}
      </span>
    </template>
  </Multiselect>
  <div v-else class="multiselect">
    <b-icon class="multiselect__caret" name="arrow-down" />
    <div class="multiselect__tags">
      <span v-if="selectLabel" class="multiselect__single">{{
        selectLabel
      }}</span>
      <span v-else class="multiselect__placeholder">{{
        props.placeholder
      }}</span>
    </div>
  </div>
</template>

<script lang="ts" setup>
import FilterCheckbox from '../checkbox/BFilterCheckbox.vue'
import FilterRadio from '../radio/BFilterRadio.vue'
import BIcon from '@/components/base/icon/BIcon.vue'

import { ISelectOption } from '@/types/ui'
import { computed, ref, onMounted } from 'vue'
// @ts-ignore
import Multiselect from 'vue-multiselect/dist/vue-multiselect.ssr'

type Value = string | string[] | null

const emit = defineEmits<{
  (e: 'update:modelValue', event: Value): void
}>()

const props = withDefaults(
  defineProps<{
    options: ISelectOption[]
    modelValue: Value
    placeholder?: string
    trackBy?: string
    label?: string
    searchable?: boolean
    multiple?: boolean
    radio?: boolean
    closeOnSelect?: boolean
    showLabels?: boolean
    allowEmpty?: boolean
  }>(),
  {
    placeholder: 'Выбрать',
    trackBy: 'id',
    label: 'name',
    searchable: false,
    multiple: false,
    closeOnSelect: false,
    showLabels: false,
    allowEmpty: false,
    radio: false
  }
)

const isChecked = (id: string): boolean => !!props.modelValue?.includes(id)

const client = ref(false)

onMounted(() => {
  client.value = true
})

const selectLabel = computed(() => {
  const optionId = props.modelValue?.[0] ?? props.modelValue
  return props.options.find((el) => el.id === optionId)?.name
})

const selected = computed<ISelectOption | ISelectOption[] | null>({
  get() {
    const val = Array.isArray(props.modelValue)
      ? props.options.filter((el) => props.modelValue?.includes(el.id))
      : props.options.find((el) => el.id === props.modelValue) || null
    return val
  },

  set(newVal: ISelectOption | ISelectOption[] | null) {
    const val = Array.isArray(newVal)
      ? newVal.map((el) => el.id)
      : newVal?.id || null
    emit('update:modelValue', val)
  }
})
</script>

<style src="vue-multiselect/dist/dist/vue-multiselect.ssr.css"></style>
<style src="./filter-select.css" scoped></style>
