import { DirectiveBinding } from 'vue'

interface IClickOutsideElement extends HTMLElement {
  clickOutsideEvent: (event: Event) => void
}

export const vClickOutside = {
  mounted(el: IClickOutsideElement, binding: DirectiveBinding) {
    el.clickOutsideEvent = (event: Event) => {
      if (!(el === event.target || el.contains(event.target as HTMLElement))) {
        binding.value(event, el)
      }
    }
    document.body.addEventListener('click', el.clickOutsideEvent)
  },
  unmounted(el: IClickOutsideElement) {
    document.body.removeEventListener('click', el.clickOutsideEvent)
  }
}
