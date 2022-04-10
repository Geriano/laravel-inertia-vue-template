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
          if (menu) {
            if (route().current(menu.route_or_url)) {
              return true
            }
            
            for (let child of menu.childs) {
              if (active(child)) {
                return true
              }
            }
          }

          return false
        }

        const size = (menu, i = 0) => {
          while (menu?.parent_id) {
            i += 1
            menu = menu.parent
          }

          return i
        }

        const generate = (menus, attrs) => {
          return menus.map(menu => {
            const childs = menu.childs
            const pl = size(menu) * 4

            if (childs.length) {
              return h(NavLinks, {
                ...attrs,
                pl,
                active: active(menu),
                title: __(menu.name),
                icon: menu.icon,
              }, generate(childs, {
                child: menu.parent_id !== null || menu.parent_id !== undefined,
              }))
            } else {
              return h(NavLink, {
                ...attrs,
                pl,
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