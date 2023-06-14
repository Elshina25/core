<template>
  <b-popover>
    <template #trigger>
      <div class="share-trigger">
        <b-icon name="share" />
        <slot v-if="showLabel" name="label">
          <span class="link link-button share-trigger__label">Поделиться</span>
        </slot>
      </div>
    </template>
    <template #body>
      <h3 class="mb-20 md:hidden">Поделиться</h3>
      <div class="mobile:flex mobile:flex-wrap">
        <a
          v-for="(item, idx) in activeLinks"
          :key="idx"
          :href="item.link"
          class="link share-link"
          target="_blank"
        >
          <b-icon
            v-if="item.icon"
            :collection="item.collection"
            :name="item.icon"
            class="absolute left-0"
          />
          <span class="t-p2 text-14">{{ item.label }}</span>
        </a>
      </div>
    </template>
  </b-popover>
</template>

<script lang="ts" setup>
import type { IShare } from '@/types'

import BPopover from '@/components/base/popover/BPopover.vue'
import BIcon from '@/components/base/icon/BIcon.vue'

import { NEW_LINE_SEPARATOR } from '@/config/share.config'
import { usePageContext } from '~/renderer/utils'
import { computed } from 'vue'

const props = withDefaults(
  defineProps<{
    socials?: string[]
    url?: string
    showLabel?: boolean
    text?: string
  }>(),
  {
    showLabel: true,
    socials: () => ['tg', 'vk', 'whatsapp', 'vb', 'mail'],
    url: '',
    text: ''
  }
)

const pageContext = usePageContext()

const link = computed(
  () =>
    props.url || `${import.meta.env.VITE_BASE_URL}${pageContext.urlOriginal}`
)

const activeLinks = computed<IShare[]>(() => {
  // Текст с разделителем для tg и email
  const separatedText = props.text.replaceAll(NEW_LINE_SEPARATOR, '%0D%0A')
  // Текст с разделителем для whatsapp и vb
  const separatedText2 = `\r\n${props.text.replaceAll(
    NEW_LINE_SEPARATOR,
    '\r\n'
  )}`

  return [
    {
      id: 'tg',
      link: `https://t.me/share/url?url=${link.value}&text=${separatedText}`,
      label: 'Telegram',
      icon: 'tg',
      collection: 'socials'
    },
    {
      id: 'vk',
      link: `http://vk.com/share.php?url=${link.value}`,
      label: 'Вконтакте',
      icon: 'vk',
      collection: 'socials'
    },
    {
      id: 'whatsapp',
      link: `https://api.whatsapp.com/send/?text=${encodeURIComponent(
        `${link.value}${separatedText2}`
      )}&type=custom_url&app_absent=0`,
      label: 'WhatsApp',
      icon: 'whatsapp',
      collection: 'socials'
    },
    {
      id: 'vb',
      link: `viber://forward?text=${encodeURIComponent(
        `${link.value}${separatedText2}`
      )}`,
      label: 'Viber',
      icon: 'vb',
      collection: 'socials'
    },
    {
      id: 'mail',
      link: `mailto:?subject=${link.value}&body=${separatedText}`,
      label: 'Эл. письмо',
      icon: 'mail-fill'
    }
  ].filter((el) => props.socials.includes(el.id))
})
</script>

<style src="./share.css" scoped></style>
