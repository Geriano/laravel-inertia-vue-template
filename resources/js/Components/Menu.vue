<script>
  import { usePage } from '@inertiajs/inertia-vue3'
  import { defineComponent, h } from 'vue'
  import NavLink from '@/Components/NavLink'
  import NavLinks from '@/Components/NavLinks'

  export default defineComponent({
    setup() {
      return props => {
        const menus = usePage().props.value.$menus

        const generate = (menus, attrs) => {
          return menus.map(menu => {
            const childs = menu.childs

            if (childs.length) {
              return h(NavLinks, {
                ...attrs,
                child: false,
                title: menu.name,
                icon: menu.icon,
              }, generate(childs, {class: 'bg-slate-600 hover:bg-slate-700 pl-4'}))
            } else {
              return h(NavLink, {
                ...attrs,
                href: menu.route_or_url.startsWith('http') ? menu.route_or_url : menu.route_or_url === '#' ? '#' : route(menu.route_or_url),
                icon: menu.icon,
                active: false,
              }, menu.name)
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