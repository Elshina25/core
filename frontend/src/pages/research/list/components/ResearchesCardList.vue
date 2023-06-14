<template>
  <div class="card-list" :class="{ 'odd-page': isPageOdd }">
    <b-card-research
      v-for="(item, idx) in items"
      :key="idx"
      :style="`order: ${idx + 1}`"
      :research="item"
      :class="{
        'is-expanded': isLastFirstCardExpanded(idx),
        'is-expanded--tablet': isCardExpandedOnTablet(idx)
      }"
      @lock="requestResearch"
    />

    <template v-if="isPageOdd">
      <person-card
        v-if="!!items.length"
        :person="person"
        class="card-list__person"
      />
      <template v-if="items.length > 4">
        <form-order-research-stub scroll-to="#personal-form" class="order-5" />
        <form-order-research
          show-only-on="desktop"
          class="order-4 md:order-5"
        />
      </template>
      <suspense>
        <form-subscribe-research v-if="items.length > 7" class="order-7" />
      </suspense>
    </template>

    <b-modal v-model="modal.status" container background>
      <form-request-research
        :research="lockResearch"
        @success="modal.close()"
      />
    </b-modal>
  </div>
</template>

<script setup lang="ts">
import { computed, Ref, ref } from 'vue'
import type { IResearch, IPerson } from '@/types'
import { useModal } from '@/composables/useModal'
import BModal from '@/components/base/modal/BModal.vue'
import BCardResearch from '@/components/base/card/BCardResearch.vue'
import FormSubscribeResearch from '@/components/form/research/FormSubscribeResearch.vue'
import FormOrderResearch from '@/components/form/research/FormOrderResearch.vue'
import FormOrderResearchStub from '@/components/form/research/FormOrderResearchStub.vue'
import FormRequestResearch from '@/components/form/research/FormRequestResearch.vue'
import PersonCard from '@/components/person/card/PersonCard.vue'
import { PHONE } from '@/config/main.config'

const props = defineProps<{
  items: IResearch[]
  currentPage: number
}>()

const person: IPerson = {
  name: 'Василий Григорьев',
  image: '/images/staff/resarchDirector.png',
  jobTitle: 'Директор отдела исследований рынка',
  phone: PHONE,
  email: 'vasiliy.grigoryev@core-xp.ru'
}

// Четная/нечетная страница
const isPageOdd = computed(() => props.currentPage % 2 === 1)

/**
 * Проверка - нужно ли увеличивать карточку - если она первая/последняя & страница нечетная
 * @param idx индекс элемента
 */
const isLastFirstCardExpanded = computed(
  () => (idx: number) => [0, 7].includes(idx) && isPageOdd.value
)

/**
 * Проверка - нужно ли увеличивать карточку на планшетах
 * @param idx индекс элемента
 */
const isCardExpandedOnTablet = computed(
  () => (idx: number) => [2].includes(idx) && isPageOdd.value
)

// TODO: Подумать над решением
const modal = useModal()
const lockResearch: Ref<IResearch> = ref(props.items[0])

/**
 * Открываем модалку для получения исследований
 */
// TODO: Подумать над решением
const requestResearch = (research: IResearch) => {
  lockResearch.value = research

  if (lockResearch.value) {
    modal.open()
  }
}
</script>

<style lang="postcss" scoped>
.card-list {
  @apply flex gap-16 md:gap-20 lg:gap-30 flex-wrap mt-20 md:mt-40;

  &__person {
    @apply order-5 md:order-2 lg:order-1;

    :deep(.person__image) {
      @apply lg:max-h-[390px];
    }
  }

  .is-expanded--tablet {
    @screen md-only {
      @apply w-full h-[358px];

      :deep(.info-title) {
        @apply t-h2;
      }

      :deep(.info-lock) {
        @apply h-20 w-20 bottom-6 -right-24;
      }
    }
  }

  .is-expanded {
    @apply h-[340px] md:h-[358px] w-full lg:h-[580px] lg:w-[clamp(50%,59vw,900px)];

    :deep(.info-title) {
      @apply md:t-h2 lg:max-w-[650px];
    }

    :deep(.info-lock) {
      @screen md {
        @apply h-20 w-20 bottom-6 -right-24;
      }

      @screen lg {
        @apply -right-30 h-26 w-26 bottom-4;
      }

      @screen xl {
        @apply -right-30 h-26 w-26 bottom-10;
      }
    }
  }
}
</style>
