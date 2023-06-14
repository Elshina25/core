<template>
  <button
    class="button w-full"
    :type="type"
    :class="[`button-${variant}`, { 'is-transparent': transparent }]"
  >
    <slot />
  </button>
</template>

<script setup lang="ts">
type Variant =
  | 'dark'
  | 'white'
  | 'white-blue'
  | 'white-green'
  | 'green'
  | 'black-outline'
type Type = 'submit' | 'button'

withDefaults(
  defineProps<{
    variant?: Variant
    type?: Type
    transparent?: boolean
  }>(),
  {
    variant: 'green',
    type: 'submit',
    transparent: false
  }
)
</script>

<style lang="postcss" scoped>
.button {
  @apply t-button h-48 md:h-54 2xl:h-60 border-2 rounded transition disabled:pointer-events-none;

  &-white {
    @apply bg-white border-white text-black disabled:opacity-60 hover:border-black;
  }

  &-black-outline {
    @apply bg-white border-black text-black border disabled:opacity-60;

    &:hover {
      @apply bg-additional-3 border-white text-white;
    }

    &:active {
      @apply bg-green text-white;
    }
  }

  &-white-blue {
    @apply bg-white border-white text-additional-3 disabled:opacity-60;

    &:hover {
      @apply bg-additional-3 border-white text-white;
    }

    &:active {
      @apply bg-white text-additional-3;
    }

    &.is-transparent {
      @apply hover:bg-transparent active:bg-white;
    }
  }

  &-white-green {
    @apply bg-white border-white text-green disabled:opacity-60;

    &:hover {
      @apply bg-green border-white text-white;
    }

    &:active {
      @apply bg-white text-green;
    }

    &.is-transparent {
      @apply hover:bg-transparent active:bg-white;
    }
  }

  &-green {
    @apply bg-green border-green text-white disabled:bg-auxiliary-5 disabled:border-auxiliary-5;

    &:hover {
      @apply bg-white border-green text-green;
    }

    &:active {
      @apply bg-green text-white;
    }

    &.is-transparent {
      @apply hover:bg-transparent active:bg-green;
    }
  }

  &-dark {
    @apply bg-black border-black text-white disabled:bg-auxiliary-5 disabled:border-auxiliary-5;

    &:hover {
      @apply bg-white border-black text-black;
    }

    &:active {
      @apply bg-black text-white;
    }

    &.is-transparent {
      @apply hover:bg-transparent active:bg-black;
    }
  }
}
</style>
