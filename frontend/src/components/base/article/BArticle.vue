<template>
  <section class="article" @click="handle($event)">
    <template v-for="(snippet, idx) in splittedContent" :key="idx">
      <div v-if="isText(snippet)" v-html="snippet.fields.content"></div>
      <div v-else-if="isCollapseGroup(snippet)" class="collapse-group">
        <div v-for="(childSnippet, i) in snippet.children" :key="i">
          <b-collapse
            :title="childSnippet.fields.title"
            :panel="!childSnippet.fields.content.trim()"
          >
            <div v-html="childSnippet.fields.content"></div>
          </b-collapse>
        </div>
      </div>
      <b-collapse
        v-else-if="isCollapse(snippet)"
        class="mt-30"
        :title="snippet.fields.title"
        :panel="!snippet.fields.content"
      >
        <div v-html="snippet.fields.content"></div>
      </b-collapse>
      <b-tabs v-else-if="isTabs(snippet)">
        <b-tab
          v-for="(tab, i) in snippet.children"
          :key="i"
          :title="tab.fields.title"
        >
          <div v-html="tab.fields.content"></div>
        </b-tab>
      </b-tabs>
    </template>
    <slot></slot>
  </section>
</template>

<script setup lang="ts">
import BCollapse from '@/components/base/collapse/BCollapse.vue'
import BTabs from '@/components/base/tabs/BTabs.vue'
import BTab from '@/components/base/tabs/BTab.vue'
import { snippets } from '@/config/article.config'
import { computed } from 'vue'

interface ISnippetSettingsItem {
  name: string
  fields?: string[]
  children?: ISnippetSettingsItem[]
}

interface ISnippet {
  name: string
  fields: Record<string, string>
  children?: ISnippet[]
}

const props = defineProps<{
  content?: string
}>()

const convertedContent = computed(() => {
	const types = '7z|gz|zip|mp3|jpg|jpeg|png|psd|xcf|tif|tiff|svg|webp|ods|xls|xlsx|csv|ppt|odp|doc|docx|odt|pdf|rtf|txt|3gp|avi|flv|m4p|mkv|mov|mp4|mpeg|mpg|ogg|wmv';
	const regExp = new RegExp('((src|href)=[\'"])(\/.*?)\.(' + types + ')([\'"])', 'gi');
	return (props.content?.replace(regExp, `$1${import.meta.env.VITE_API_URL}$3.$4$5`) ?? '')
})

// Методы для проверки типа сниппета
const isText = (snippet: ISnippet): boolean => snippet.name === 'text'
const isCollapse = (snippet: ISnippet): boolean => snippet.name === 'collapse'
const isTabs = (snippet: ISnippet): boolean => snippet.name === 'tabs'
const isCollapseGroup = (snippet: ISnippet): boolean =>
  snippet.name === 'collapse-group'

/**
 * Метод возвращает вложенные значения тега
 * @param content
 * @param tagName
 * @example
 * getTagContent("<p>text inside paragraph</p> <tag>some text</tag> text outside of tags", "tag")
 * returns ['some text']
 */
const getTagContent = (content: string, tagName: string): string[] => {
  const regex = new RegExp('<' + tagName + '>.*?</' + tagName + '>', 'gsm')
  return content.match(regex) ?? ['']
}

/**
 * Метод собирает объект с значениями полей сниппета
 * @param content
 * @param fields
 * @example
 * getSnippetFieldValues("<title>title text</title> <content>content text</content>", ["content", "title"])
 * returns { title: "title text", content: "content text" }
 */
const getSnippetFieldValues = (
  content: string,
  fields: string[]
): Record<string, string> =>
  fields.reduce((acc: Record<string, string>, field: string) => {
    // Получаем значение поля и записываем его в свойство объекта
    const text = getTagContent(content, field)[0]

    if (text) {
      const regex = new RegExp('<' + field + '>(.*?)</' + field + '>', 'gsm')
      acc[field] = text.replace(regex, `$1`)
    } else {
      acc[field] = text
    }

    return acc
  }, {})

/**
 * Метод собирает объект сниппета
 * @param content
 * @param snippetSettings
 * @example
 * getSnippet(`
 *  <collapse>
 *    <title>title text</title>
 *    <content>content text</content>
 *  </collapse>
 *`, { name: 'collapse', fields: ['title', 'content'] })
 *
 * returns { name: 'collapse', fields: { title: 'title text', content: 'content text' } }
 */
const getSnippet = (
  content: string,
  snippetSettings: ISnippetSettingsItem
): ISnippet => {
  // Формируем объект сниппета
  const snippet: ISnippet = {
    name: snippetSettings.name,
    fields: {}
  }

  // Если у сниппета есть поля, формируем объект с значениями полей и сохраняем
  if (snippetSettings.fields?.length) {
    snippet.fields = getSnippetFieldValues(content, snippetSettings.fields)
  }
  // Если нет дочерних элементов, то возвращаем объект сниппета
  if (!snippetSettings.children?.length) return snippet
  // Объявляем массив с дочерними элементами
  const childrenList: ISnippet[] = []
  // Проходим по массиву дочерних элементов сниппета
  snippetSettings.children.forEach((child: ISnippetSettingsItem) => {
    // Список из вложенных значений дочерних элементов
    const childContentList: string[] = getTagContent(content, child.name)
    // Собираем список дочерних сниппетов
    const childrenSnippet: ISnippet[] = childContentList.map(
      (content: string) => getSnippet(content, child)
    )
    // Сохраняем дочерние сниппеты
    childrenList.push(...childrenSnippet)
  })
  snippet.children = childrenList
  return snippet
}

const splittedContent = computed<ISnippet[]>(() => {
  if (!convertedContent.value) return []
  return (
    convertedContent.value
      // формируем массив из текста и сниппетов
      ?.split(/<\/?(?:snippet)[^>]*>\s*/im)
      // фильтруем пустые значения
      ?.filter((el: string): boolean => !!el)
      // проходим по значениям и собираем объект сниппета
      .map((content: string) => {
        // проверяем является ли элемент сниппетом или просто текст
        const snippet: ISnippetSettingsItem | undefined = snippets.find(
          // TODO Vlad: подумать как лучше по другому определять тип сниппета
          (snippet: ISnippetSettingsItem) => content.includes(snippet.name)
        )
        // собираем и возвращаем объект сниппета текста
        if (!snippet) return { name: 'text', fields: { content } }
        // возвращаем сниппет
        return getSnippet(content, snippet)
      })
  )
})

/**
 * Открываем все картинки в новом окне
 * @param e
 */
function handle(e: MouseEvent) {
  const t = e.target as HTMLImageElement
  if (t.tagName === 'IMG') {
    if (!t.closest('a')) {
      window.open(t.src)
    }
  }
}
</script>

<style src="./article.css" scoped></style>
