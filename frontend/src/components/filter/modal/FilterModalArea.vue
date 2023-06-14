<template>
  <div class="modal">
    <div class="modal-head modal-head--mobile">
      <b-icon
        class="mr-16 rotate-90"
        name="arrow-down"
        @click="modal.close()"
      />
      <b-filter-input
        v-model="search"
        class="modal-head__search"
        placeholder="Название"
      />
    </div>
    <div class="modal-head modal-head--desktop">
      <div>
        <b-filter-btn
          v-for="(tab, idx) in tabs"
          :key="`modal${idx}`"
          variant="link"
          class="modal-head__btn"
          :class="{
            'modal-head__btn--active': activeTab?.id === tab.id
          }"
          @click="$emit('on-click-tab', tab)"
        >
          {{ tab.label }}
        </b-filter-btn>
      </div>
      <!-- TODO Vlad: временно закомментил -->
      <div v-if="false" class="flex items-center">
        <span class="t-button mr-26">Москва и МО</span>
        <div
          class="2xl:absolute 2xl:right-20 2xl:top-20 hover:text-green cursor-pointer"
          @click="modal.close()"
        >
          <b-icon name="close" />
        </div>
      </div>
    </div>
    <div class="modal-body">
      <div
        class="modal-body__group"
        :style="`--grid-element-count: ${activeValues.length}`"
      >
        <b-filter-checkbox
          v-for="item in activeValues"
          :key="item.id + item.name"
          v-model="value"
          :label="item.name"
          :value="getItemValue(item)"
          :variant="checkboxVariant"
          class="modal-body__item"
        >
          <template #before>
            <b-icon
              v-if="showMetroIcon"
              class="modal-body__underground-icon"
              name="underground"
            />
          </template>
        </b-filter-checkbox>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ModalSymbol } from '@/components/filter/base/modal/injectionKeys'
import BIcon from '@/components/base/icon/BIcon.vue'
import BFilterBtn from '@/components/filter/base/button/BFilterButton.vue'
import BFilterCheckbox from '@/components/filter/base/checkbox/BFilterCheckbox.vue'
import BFilterInput from '@/components/filter/base/input/BFilterInput.vue'

import type { IFilterArea, CheckboxVariant, IFilterAreaValue } from '@/types/ui'
import { useModelValue } from '@/composables/useModelValue'
import { injectStrict } from '@/utils'
import { ref, onMounted, onUnmounted, computed } from 'vue'

type Selected = Array<string | number>

defineEmits<{
  (e: 'on-click-tab', event: IFilterArea): void
  (e: 'update:modelValue', event: Selected): void
}>()

const props = defineProps<{
  tabs: IFilterArea[]
  modelValue: Selected
  activeTab?: IFilterArea
}>()

const { value } = useModelValue(props)

const modal = injectStrict(ModalSymbol)
const checkboxVariant = ref('default' as CheckboxVariant)

const search = ref('')
const activeValues = computed(() => {
  const values = props.activeTab?.values ?? []
  return !search.value
    ? values
    : values.filter((el) =>
        el.name.toLocaleLowerCase().includes(search.value.toLocaleLowerCase())
      )
})

const showMetroIcon = computed(
  () => props.activeTab?.id === 'metro' && checkboxVariant.value === 'inline'
)

const getItemValue = (item: IFilterAreaValue) => {
  if (props.activeTab?.id === 'metro') {
    return item.slug
  }
  return item.id
}

onMounted(() => {
  onResize()
  window.addEventListener('resize', onResize)
})

const onResize = () => {
  if (window.innerWidth < 768) {
    checkboxVariant.value = 'inline'
    return
  }
  checkboxVariant.value = 'default'
}

onUnmounted(() => {
  window.removeEventListener('resize', onResize)
})
</script>

<style src="./filter-modal-area.css" scoped></style>
