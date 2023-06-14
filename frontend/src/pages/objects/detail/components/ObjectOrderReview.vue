<template>
  <div>
    <h1 class="mb-30 lg:mb-60">Заявка на просмотр</h1>
    <div class="form">
      <form-object-order :crm-id="object.crmId" />
      <div class="form__static">
        <img :src="getFullUrl(object.images[0])" class="form__static-img" />
        <div>
          <h3 class="md-only:t-h2 mb-10 md:mb-20">{{ object.title }}</h3>
          <div
            class="description"
            :class="{ short: !showDescriptionAll }"
            v-html="object.description"
          />
          <div
            class="description-toggle nested-link"
            @click="showDescriptionAll = !showDescriptionAll"
          >
            <span class="link link-button">{{
              showDescriptionAll ? 'Свернуть описание' : 'Развернуть описание'
            }}</span
            ><b-icon
              name="arrow-down-rounded"
              class="link link-icon"
              :class="{ 'top-2 rotate-180': showDescriptionAll }"
            />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import BIcon from '@/components/base/icon/BIcon.vue'
import FormObjectOrder from '@/components/form/object/FormObjectOrder.vue'
import { getFullUrl, injectStrict } from '@/utils'
import { KEY_OBJECT } from '@/pages/objects/detail/injectionKeys'

const object = injectStrict(KEY_OBJECT)

const showDescriptionAll = ref(false)
</script>

<style scoped lang="postcss">
.form {
  @apply bg-white rounded flex flex-col lg:flex-row;
  &__static {
    @apply p-16 mobile:py-30  md:p-26 md:pb-40 xl:p-50 lg:w-[clamp(400px,80vw,640px)] xl:min-w-[540px] xl:w-[540px];
    @apply md-only:flex gap-24;

    &-img {
      @apply rounded mb-24 md:mb-0 lg:mb-32 object-cover;
      @apply w-full h-[249px] md-only:w-[260px] md:h-[260px] lg:h-[291px];
    }
  }
}

:deep(.description) {
  > p {
    @apply t-p3 md-only:t-p2 mb-[9px];
  }
}

.description {
  &.short {
    @apply overflow-hidden;
    -webkit-line-clamp: 6;
    display: -webkit-box;
    -webkit-box-orient: vertical;
  }
}

.description-toggle {
  @apply inline-flex items-center gap-6 mt-16 t-p3 md-only:t-p2 cursor-pointer;

  .link-icon {
    @apply w-16 h-16 relative;
  }
}
</style>
