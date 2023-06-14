<template>
  <div v-if="list.length" class="buildings">
    <b-card-building
      v-for="item in objects.before"
      :key="item.id"
      :building="item"
    />
  </div>
  <template v-else>
    <slot name="no-result">
      <list-no-result
        class="mt-30 md:mt-40 2xl:mt-60"
        link-label="страницу поисковой выдачи"
        link="/estate/office"
      />
    </slot>
  </template>

  <form-object-consult
    v-if="objects.after.length"
    title="Заказать помощь в подборе"
    description="Если вам нужна консультация или помощь в подборе, оставьте ваши контакты, и мы свяжемся с вами в ближайшее время"
    class="my-16 md:my-60 2xl:my-80"
  />
  <div v-if="objects.after.length" class="buildings">
    <b-card-building
      v-for="item in objects.after"
      :key="item.id"
      :building="item"
    />
  </div>
</template>

<script lang="ts" setup>
import ListNoResult from '@/components/table/ListNoResult.vue'
import FormObjectConsult from '@/components/form/object/FormObjectConsult.vue'
import BCardBuilding from '@/components/base/card/BCardBuilding.vue'
import { computed } from 'vue'
import { ObjectListResponse } from '@/api/object/list'

const props = defineProps<{
  list: ObjectListResponse[]
}>()

const objects = computed(() => ({
  before: props.list.slice(0, 6) || [],
  after: props.list.slice(6, props.list.length) || []
}))
</script>

<style lang="postcss" scoped>
.buildings {
  @apply grid grid-cols-1 gap-y-16
  md:grid-cols-2 md:gap-20
  xl:grid-cols-3 xl:gap-30
  mt-20 md:mt-40;
}
</style>
