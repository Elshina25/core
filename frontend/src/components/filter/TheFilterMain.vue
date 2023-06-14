<template>
  <div class="filters">
    <b-filter-form-generator :fields="FILTER_FORM_MAIN" />
  </div>
</template>

<script lang="ts" setup>
import BFilterFormGenerator from './base/form-generator/BFilterFormGenerator.vue'
import { FILTER_FORM_MAIN } from '@/config/filter/filter-form.config'
</script>

<style lang="postcss" scoped>
.filters {
  @apply grid gap-10 bg-white p-16 md:pb-16 xl:p-20 xl:pb-20 rounded-r rounded-b;
  grid-template:
    1fr / clamp(100px, 12vw, 190px) clamp(100px, 12vw, 180px) 314px
    clamp(80px, 15vw, 114px) auto;

  @screen mobile {
    grid-template: 1fr / calc(50% - 5px) calc(50% - 5px);
  }

  @screen md-only {
    grid-template: 1fr 1fr / clamp(174px, 20vw, 33%) clamp(270px, 35vw, 50%) clamp(
        112px,
        20vw,
        17%
      );
  }

  :deep(.filters) {
    &__range {
      @apply w-full;

      .filter-element {
        @apply w-full;
      }

      @screen mobile {
        grid-area: 2 / 1 / 2 / 3;
      }

      @screen md-only {
        grid-area: 1 / 2 / 2 / 4;
      }
    }

    &__search {
      @screen mobile {
        grid-column: 1 / 3;
      }

      @screen md-only {
        grid-area: 2 / 2 / 3 / 5;

        .filter-element {
          @apply max-w-none;
        }
      }
    }

    &__select {
      @screen mobile {
        grid-column: 1 / 3;
      }

      &--city {
        @screen md-only {
          grid-area: 2 / 1 / 3 / 1;
        }
      }

      &--class {
        @screen mobile {
          grid-area: 3 / 1 / 4 / 3;
        }
      }

      &--space {
        @screen mobile {
          grid-row: 1 / 2;
        }

        @screen md-only {
          grid-area: 1 / 1 / 2 / 2;
        }
      }
    }
  }
}
</style>
