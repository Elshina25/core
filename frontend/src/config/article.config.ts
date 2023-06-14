// Список сниппетов
const collapseSnippet = {
  name: 'collapse',
  fields: ['title', 'content']
}

export const snippets = [
  {
    name: 'collapse-group',
    children: [collapseSnippet]
  },
  {
    name: 'tabs',
    children: [{ name: 'tab', fields: ['title', 'content'] }]
  },
  collapseSnippet
]
