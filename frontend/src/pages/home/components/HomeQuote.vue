<template>
  <div class="container">
    <section class="quote">
      <div ref="video" class="quote__video">
        <div
          v-if="preview"
          class="quote__video-preview"
          title="Воспроизвести видео"
          @click="play"
        >
          <div class="play">
            <svg
              fill="none"
              height="27"
              viewBox="0 0 23 27"
              width="23"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                d="m22.5 12.2598c.6667.3849.6667 1.3472 0 1.7321l-21 12.1243c-.666668.3849-1.50000003-.0962-1.5-.866l.00000106-24.24871c.00000003-.7698.83333394-1.250923 1.49999894-.866023z"
                fill="#09ad67"
              />
            </svg>
          </div>
        </div>
      </div>
      <div class="quote__info">
        <div class="quote__info-text">
          <p>
            &laquo;Представляем наш новый бренд&nbsp;&mdash;
            <span>CORE.XP</span>.
          </p>
          <p>
            CORE.XP является преемником российского бизнеса
            <a href="https://www.cbre.com/" target="_blank" class="link">CBRE</a
            >, мирового лидера в&nbsp;консалтинге и управлении инвестициями.
          </p>
          <p>
            Мы&nbsp;&mdash; CORE.XP&nbsp;&mdash; Эксперты в&nbsp;недвижимости.
            Преемственность&nbsp;&mdash; наша основа.
          </p>
          <p><span>Клиенты&nbsp;&mdash; центр нашего бизнеса.</span>&raquo;</p>
        </div>
        <div class="quote__info-director">
          <img
            src="/images/home/director.png"
            alt="Генеральный директор CORE.XP"
          />
          <div>
            <div class="name">Владимир Пинаев</div>
            <div class="position">Генеральный директор CORE.XP</div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { getFullUrl } from '@/utils'

const preview = ref(true)
const video = ref()

const play = () => {
  preview.value = false
  video.value.innerHTML = `
    <video autoplay muted controls>
      <source
        src="${getFullUrl('/upload/video/Final.mp4')}"
        type="video/mp4"
      />
    </video>
  `
}
</script>

<style lang="postcss" scoped>
.quote {
  @apply flex flex-col lg:flex-row bg-white rounded
    gap-16 md:gap-26 lg:gap-40
    p-16 md:p-26 lg:p-40;

  &__video {
    @apply relative flex-none rounded overflow-hidden
      w-full h-auto lg:max-h-[clamp(200px,25vw,355px)] lg:w-[clamp(300px,50%,593px)] aspect-video;

    &-preview {
      @apply w-full h-full inset-0 absolute z-10 rounded overflow-hidden cursor-pointer;
      @apply bg-cover lg:bg-contain bg-center
        mobile:bg-[url('/images/home/video-stub-mobile.png')]
        md:bg-[url('/images/home/video-stub-tablet.png')]
        lg:bg-[url('/images/home/video-stub.png')];

      .play {
        @apply flex justify-center items-center absolute bg-white/50 transition-background
        bottom-10 right-10 w-24 h-24 rounded-[6px]
        md:bottom-16 md:right-16 md:w-50 md:h-50 md:rounded
        lg:bottom-24 lg:right-24 lg:w-60 lg:h-60;

        svg {
          @apply fill-green
            w-[10px] h-[11px]
            md:w-[30px] md:h-[23px]
            lg:w-[23px] lg:h-[27px];
        }
      }

      &:hover {
        .play {
          @apply bg-white;
        }
      }
    }

    :deep(video) {
      @apply w-full h-full rounded overflow-hidden border border-white;
    }
  }

  &__info {
    @apply flex md-only:flex-row flex-col gap-20 md:gap-12 lg:gap-30;

    &-text {
      @apply md-only:flex-none md-only:w-[420px];

      p {
        @apply md-only:t-h3 t-h4 mb-8 md:mb-20 last:mb-0;

        span {
          @apply text-green;
        }
      }
    }

    &-director {
      @apply flex items-center md-only:flex-col md-only:items-baseline
        gap-12 md:gap-20 lg:gap-30;

      img {
        @apply w-80 h-80 md:w-100 md:h-100 lg:w-[130px] lg:h-[130px] rounded;
      }

      .name {
        @apply md-only:t-h3 t-h4 mb-4 md:mb-8;
      }

      .position {
        @apply t-p3 md:t-p2;
      }
    }
  }
}
</style>
