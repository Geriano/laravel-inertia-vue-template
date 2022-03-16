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

        const generate = (menus, attrs) => {
          return menus.map(menu => {
            const childs = menu.childs

            if (childs.length) {
              const active = menu.routes.find(name => route().current(name)) !== undefined || menu.childs.find(child => child.routes.find(name => route().current(name))) !== undefined

              return h(NavLinks, {
                ...attrs,
                active,
                title: __(menu.name),
                icon: menu.icon,
              }, generate(childs, {
                child: menu.parent_id !== null || menu.parent_id !== undefined,
              }))
            } else {
              const active = menu.routes.find(name => route().current(name)) !== undefined

              return h(NavLink, {
                ...attrs,
                href: menu.route_or_url.startsWith('http') ? menu.route_or_url : menu.route_or_url === '#' ? '#' : route(menu.route_or_url),
                icon: menu.icon,
                active,
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