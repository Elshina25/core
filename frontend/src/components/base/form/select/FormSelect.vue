<template>
  <div class="input-group">
    <label v-if="label" :for="id">{{ label }}</label>
    <div class="input-group__el relative">
      <Multiselect
        v-bind="settings"
        v-model="selected"
        :placeholder="placeholder"
        :options="options"
        track-by="code"
        label="name"
      >
        <template #caret>
          <b-icon class="multiselect__caret" name="arrow-down" />
        </template>
        <template #option="{ option }">
          <filter-checkbox
            :checked="some(selected, option)"
            :label="option.name"
            disabled
            class="!mb-0"
          />
        </template>
        <template #selection="{ values }">
          <span v-if="values.length" class="multiselect__value">
            {{ inputValue }}
          </span>
        </template>
      </Multiselect>
    </div>
  </div>
</template>

<script lang="ts" setup>
import some from 'lodash-es/some'
import uniqueId from 'lodash-es/uniqueId'
import BIcon from '@/components/base/icon/BIcon.vue'
import FilterCheckbox from '@/components/filter/base/checkbox/BFilterCheckbox.vue'
// @ts-ignore
import Multiselect from 'vue-multiselect/dist/vue-multiselect.ssr'
import { computed } from 'vue'
import { ISelectTag } from '@/types/ui'

interface Settings {
  searchable: boolean
  multiple: boolean
  closeOnSelect: boolean
  showLabels: boolean
}

const emit = defineEmits<{
  (e: 'update:modelValue', event: ISelectTag[]): void
}>()

const props = withDefaults(
  defineProps<{
    options: ISelectTag[]
    modelValue: ISelectTag[]
    placeholder?: string
    label: string
    settings?: Settings
  }>(),
  {
    placeholder: 'Выбрать',
    settings: () => ({
      searchable: false,
      multiple: true,
      closeOnSelect: false,
      showLabels: false
    })
  }
)

const id = uniqueId('select-')

const selected = computed({
  get() {
    return props.modelValue
  },

  set(val: ISelectTag[]) {
    //Если присутствует элемент "Все" и элементов > 1
    if (val.length > 1 && some(val, (element) => element.code === 'all')) {
      const emittedVal =
        val[val.length - 1].code == 'all'
          ? // Если "Всё" выбран последним, эммитим его
            [val[val.length - 1]]
          : //Если нет, то эмитим всё, кроме элемента "Всё"
            val.filter((element) => element.code !== 'all')

      emit('update:modelValue', emittedVal)
    } else {
      emit('update:modelValue', val)
    }
  }
})

const inputValue = computed(() =>
  selected.value.map((item) => item.name).join(', ')
)
</script>
<style src="vue-multiselect/dist/dist/vue-multiselect.ssr.css"></style>
<style src="./select.css" scoped></style>
