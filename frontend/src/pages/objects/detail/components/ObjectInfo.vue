<template>
  <div>
    <h2 class="mb-20 lg:mb-40">Описание объекта</h2>
    <div v-if="factoids.length" class="factoid-container">
      <object-factoid
        v-for="(factoid, idx) in factoids"
        :key="idx"
        :factoid="factoid"
      />
    </div>
    <b-article :content="object.description" />
  </div>
</template>

<script setup lang="ts">
import { injectStrict } from '@/utils'
import ObjectFactoid from '../components/ObjectFactoid.vue'
import { KEY_OBJECT } from '@/pages/objects/detail/injectionKeys'
import BArticle from '@/components/base/article/BArticle.vue'
import { computed } from 'vue'
import { IObjectFactoid } from '@/types/objects'

const object = injectStrict(KEY_OBJECT)

const factoids = computed<IObjectFactoid[]>(() => {
  const res = [] as IObjectFactoid[]

  if (object.value.leaseTotal) {
    res.push({
      value: object.value.leaseTotal + ' м²',
      title: 'Общая арендуемая площадь'
    })
  }

  if (object.value.leaseSquare) {
    res.push({
      value: object.value.leaseSquare + ' м²',
      title: 'Площади в аренду'
    })
  }

  if (object.value.parkingType) {
    res.push({ value: object.value.parkingType, title: 'Парковка' })
  }

  if (object.value.operationHeight) {
    res.push({
      value: object.value.operationHeight + ' м',
      title: 'Высота потолка'
    })
  }

  if (object.value.columnGridMin || object.value.columnGridMax) {
    let stepColumns = ''

    if (object.value.columnGridMin)
      stepColumns += 'от ' + object.value.columnGridMin

    if (object.value.columnGridMax)
      stepColumns += ' до ' + object.value.columnGridMax

    res.push({ value: stepColumns, title: 'Шаг колонн' })
  }

  return res
})
</script>

<style scoped lang="postcss">
.factoid-container {
  @apply flex flex-wrap gap-6 md:gap-12 lg:gap-20 mb-20 md:mb-30 lg:mb-40;
}
</style>
