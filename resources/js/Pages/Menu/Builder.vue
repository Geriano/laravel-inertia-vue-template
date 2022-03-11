<script>
  import { defineComponent, h } from 'vue'
  import Container from './Container'
  import Menu from './Menu'

  export default defineComponent({
    props: {
      menus: Array,
    },

    setup(props, { attrs }) {
      var menus = []

      const generateMenuComponent = (menus, attrs) => {
        return menus.map(menu => {
          const childs = menu.childs || []

          if (childs.length) {
            return h(Container, {
              ...attrs,
              class: 'ml-0',
              menus,
              menu,
            }, generateMenuComponent(childs))
          } else {
            return h(Menu, {
              ...attrs,
              menus,
              menu,
            })
          }
        })
      }

      return props => {
        menus = props.menus

        return h('div', {
          ...attrs,
          class: 'flex flex-col space-y-1',
        }, generateMenuComponent(menus))
      }
    },
  })
</script>