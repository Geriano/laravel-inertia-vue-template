<script>
  import { usePage } from '@inertiajs/inertia-vue3'
  import { defineComponent, h } from 'vue'
  import NavLink from '@/Components/NavLink'
  import NavLinks from '@/Components/NavLinks'

  export default defineComponent({
    setup() {
      return props => {
        const menus = Array.isArray(usePage().props.value.$menus)
          ? usePage().props.value.$menus
          : Object.values(usePage().props.value.$menus)

        const active = menu => {
          if (route().current(menu.route_or_url)) {
            return true
          }
          
          for (let child of menu.childs) {
            if (active(child)) {
              return true
            }
          }

          return false
        }

        const generate = (menus, attrs) => {
          return menus.map(menu => {
            const childs = menu.childs

            if (childs.length) {
              return h(NavLinks, {
                ...attrs,
                active: active(menu),
                title: __(menu.name),
                icon: menu.icon,
              }, generate(childs, {
                child: menu.parent_id !== null || menu.parent_id !== undefined,
              }))
            } else {
              return h(NavLink, {
                ...attrs,
                href: menu.route_or_url.startsWith('http') ? menu.route_or_url : menu.route_or_url === '#' ? '#' : route(menu.route_or_url),
                icon: menu.icon,
                active: active(menu),
              }, __(menu.name))
            }
          })
        }

        return h('div', {
          class: 'flex flex-col',
        }, generate(menus))
      }
    }
  })
</script>