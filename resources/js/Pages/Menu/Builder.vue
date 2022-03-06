<template>
  <!-- generating tailwind classes -->
  <div class="hidden ml-8 ml-12 ml-16 ml-20 ml-24 ml-28 ml-32 ml-36 ml-40 ml-44 ml-48 ml-52 ml-56 ml-60 ml-64 ml-68 ml-72 ml-76 ml-80 ml-84 ml-88"></div>
</template>

<script>
  import { defineComponent, h } from 'vue'
  import Menu from './Menu'

  export default defineComponent({
    props: {
      menus: Array,
    },

    setup(props, { attrs }) {
      var menus = []

      const generateMenuComponent = (menus, attrs) => {
        const size = menu => {
          let current = menu
          let result = 4

          while (current && current.parent_id !== null) {
            current = menus.find(menu => menu.id === current.parent_id)
            result += 4
          }

          return result
        }

        return menus.map(menu => {
          const childs = menu.childs || []

          if (childs.length) {
            return h('div', {
              ...attrs,
              menu,
            }, [
              h(Menu, {
                ...attrs,
                class: 'ml-0',
                menu,
              }),
              ...generateMenuComponent(childs, {class: 'ml-8'})
            ])
          } else {
            return h(Menu, {
              ...attrs,
              menu,
            })
          }
        })
      }

      return props => {
        menus = props.menus

        return h('div', {
          ...attrs,
          class: 'flex flex-col',
        }, generateMenuComponent(menus))
      }
    },
  })
</script>