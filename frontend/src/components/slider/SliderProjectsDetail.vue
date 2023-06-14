<template>
  <div class="container pb-4">
    <a :href="$ROUTE.PROJECTS.slug + `/${project.slug}`" class="card-project">
      <img :src="getFullUrl(project.image)" :title="project.name" alt="" />
      <div class="card-project__content">
        <div v-if="project.section.name || project.type.name" class="badges">
          <b-badge v-if="project.section.name">
            {{ project.section.name }}
          </b-badge>
          <b-badge v-if="project.type.name">{{ project.type.name }}</b-badge>
        </div>
        <div class="mb-[200px] md:mb-24 lg:mb-150">
          <span class="relative">
            <div class="card-project__content-title link link-white">
              {{ project.preview }}
            </div>
          </span>
        </div>
        <div v-if="project.facts.length" class="factoids">
          <div
            v-for="(factoid, idx) in project.facts"
            :key="idx"
            class="factoid"
          >
            <div
              class="title"
              v-html="convertFactTitle(factoid.description)"
            ></div>
            <div class="subtitle" v-html="factoid.title"></div>
          </div>
        </div>
      </div>
      <div class="card-project__bg-2"></div>
      <div class="card-project__bg-3"></div>
    </a>
    <a class="link t-p2" :href="$ROUTE.PROJECTS.slug">Все проекты</a>
  </div>
</template>

<script setup lang="ts">
import BBadge from '@/components/base/badge/BBadge.vue'
import { IProjectSlide } from '@/types'
import { getFullUrl } from '@/utils'
import { convertFactTitle } from '@/utils/index'

defineProps<{
  project: IProjectSlide
}>()
</script>

<style lang="postcss" scoped>
.card-project {
  @apply block bg-white rounded relative mb-20 md:mb-30 lg:mb-40
    w-[calc(100%-10px)] md:w-[calc(100%-24px)] lg:w-[calc(100%-60px)];

  img {
    @apply absolute rounded inset-0 w-full h-full object-cover object-center z-[1];
  }

  &:before {
    @apply content-[''] absolute inset-0 bg-[#000]/30 z-[2] rounded;
  }

  &__content {
    @apply flex flex-col h-auto md:min-h-[50vw] lg:min-h-[550px] xl:min-h-[646px] p-16 mobile:pb-32 md:p-30 lg:p-60 relative z-[3];

    .badges {
      @apply flex flex-wrap gap-6 lg:gap-10 mb-30 md:mb-26 lg:mb-[46px] min-h-[20px] md:min-h-[24px] lg:min-h-[28px];
    }

    &-title {
      @apply t-h2 xl:w-[650px] relative;
      word-break: break-word; /* TODO: попробовать установить глобально */
    }

    .factoids {
      @apply flex mobile:gap-20 mobile:flex-col md:justify-between mt-auto mb-0;

      .factoid {
        @apply flex-none w-fit;

        .title {
          @apply t-h4 text-white mb-2 md:mb-4 md:w-[220px] xl:w-[340px];

          :deep(span) {
            @apply md-only:t-h2 t-h1;
          }
        }

        .subtitle {
          @apply max-w-[205px] xl:max-w-[340px];
          @apply t-p3 lg:t-p2 text-white;
        }
      }
    }
  }

  &__bg-2 {
    @apply w-full absolute bg-[#7ED0AD] -z-[10] rounded;
    @apply -right-8 top-8 bottom-8;

    @screen md {
      @apply -right-12 top-12 bottom-12;
    }

    @screen lg {
      @apply -right-30 top-30 bottom-30;
    }
  }

  &__bg-3 {
    @apply w-full absolute bg-green -z-[20] rounded;
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
