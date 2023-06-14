<template>
  <div class="container pb-[200px]">
    <div class="card-person">
      <div class="card-person__content">
        <div class="info">
          <div class="info__name t-article">{{ person.name }}</div>
          <div v-if="person.previewText" class="info__projects">
            <p class="info__projects-label t-p2">Клиенты и проекты</p>
            <div class="info__preview" v-html="person.previewText"></div>
          </div>
          <b-collapse v-if="experienceHistory" title="Опыт">
            <div v-html="experienceHistory"></div>
          </b-collapse>
        </div>

        <div class="employee">
          <img
            v-if="person.imageDetail"
            class="employee__photo"
            :src="getFullUrl(person.imageDetail)"
          />
          <div v-else class="employee__photo"></div>

          <div class="employee__name t-article">{{ person.name }}</div>
          <div class="employee__position t-p2 md-only:t-p3">
            {{ person.jobTitle }}
          </div>

          <div
            v-if="person.yearExperience"
            class="employee__experience t-button md-only:!hidden"
          >
            <span class="status"></span>
            {{ person.yearExperience }} опыта
          </div>
        </div>
      </div>

      <div class="card-person__bottom">
        <b-slider-navigator />

        <div
          v-if="person.yearExperience"
          class="employee__experience t-button !hidden md-only:!flex"
        >
          <span class="status"></span>
          {{ person.yearExperience }} опыта
        </div>

        <b-dropdown
          v-if="contacts.length"
          label="Связаться"
          variant="dark"
          :items="contacts"
          class="w-full md:w-[168px] lg:w-[242px]"
        >
          <template #default="{ item }">
            <a v-if="!item.isTextLink" :href="item.value">
              <b-dropdown-item
                :text="item.label"
                :icon="item.icon"
                :collection="item.collection"
              />
            </a>

            <b-dropdown-item
              v-else
              :icon="item.icon"
              :collection="item.collection"
            >
              <a class="transition-none" :href="item.value">{{ item.label }}</a>
            </b-dropdown-item>
          </template>
        </b-dropdown>
      </div>
      <div class="card-person__bg-2"></div>
      <div class="card-person__bg-3"></div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { IPersonTeam } from '@/types'
import { getFullUrl } from '@/utils'
import { getPersonContacts } from '@/utils/show'
import BDropdown from '@/components/base/dropdown/BDropdown.vue'
import BDropdownItem from '@/components/base/dropdown/BDropdownItem.vue'
import BCollapse from '@/components/base/collapse/BCollapse.vue'
import BSliderNavigator from '../base/slider/BSliderNavigator.vue'

const props = defineProps<{
  person: IPersonTeam
}>()

const experienceHistory = computed(() =>
  props.person.experienceHistory?.replaceAll('&lt;br&gt;', '<br />')
)

const contacts = computed(() => getPersonContacts(props.person))
</script>

<style lang="postcss" scoped>
.card-person {
  @apply bg-white rounded relative
    w-[calc(100%-10px)] md:w-[calc(100%-24px)] lg:w-[calc(100%-60px)]
    p-16 md:p-30 lg:p-60;

  &__content {
    @apply flex mobile:flex-col-reverse md-only:flex-row-reverse
      gap-18 md:gap-22 lg:gap-80
      mb-30 md:mb-20 lg:mb-56;

    .info {
      @apply w-full;

      &__preview {
        @apply t-p1;
      }

      &__name {
        @apply mobile:hidden mb-14 lg:mb-40;
      }

      &__projects {
        &-label {
          @apply mb-6 md:mb-8 lg:mb-18;
        }
      }

      :deep(.collapse) {
        @apply h-fit w-fit p-0 mt-30 min-h-fit bg-transparent;

        &__head {
          @apply t-p2 w-fit pr-22 min-h-fit bg-transparent;
        }

        &__arrow {
          @apply w-16 h-16 top-2;
        }

        &__content {
          @apply t-p1;

          &--active {
            @apply max-h-[1000px] mt-18;
          }
        }
      }
    }
  }

  &__bottom {
    @apply w-full;

    @screen md {
      @apply flex justify-between items-center;
    }

    .swiper-navigator {
      @apply tablet:hidden;
    }

    .contacts {
      @apply md:ml-auto w-full md:w-[180px] lg:w-[242px];
    }
  }

  .employee {
    @apply w-full md:w-[180px] lg:w-[242px] flex-none;

    &__photo {
      @apply h-[246px] w-[246px] md:w-[180px] md:h-[180px] lg:h-[242px] lg:w-[242px] rounded mb-12 md:mb-16;
      @apply bg-auxiliary-6/50 object-cover object-top;
    }

    &__name {
      @apply block md:hidden mb-4;
    }

    &__position {
      @apply mb-10 md:mb-20;
    }

    &__experience {
      @apply flex items-center gap-6 md:gap-8 lg:gap-12 md-only:text-14;

      .status {
        @apply w-12 h-12 md:w-14 md:h-14 lg:w-20 lg:h-20 rounded-full bg-green;
      }
    }
  }

  &__bg-2 {
    @apply w-full absolute bg-[#7ED0AD] -z-[1] rounded;
    @apply -right-8 top-8 bottom-8;

    @screen md {
      @apply -right-12 top-12 bottom-12;
    }

    @screen lg {
      @apply -right-30 top-30 bottom-30;
    }
  }

  &__bg-3 {
    @apply w-full absolute bg-green -z-[2] rounded;
    @apply -right-16 top-16 bottom-16;

    @screen md {
      @apply -right-24 top-24 bottom-24;
    }

    @screen lg {
      @apply -right-60 top-60 bottom-60;
    }
  }
}
</style>
