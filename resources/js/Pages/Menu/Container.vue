<template>
  <div :class="menu.parent_id !== null && 'border-r-0'" class="flex flex-col space-y-1">
    <Menu :menus="menus" :menu="menu" :minimize="true" :open="open" @toggle="open = ! open" class="w-full" />

    <transition name="fade">
      <div v-if="open" class="flex flex-col space-y-1 ml-8">
        <slot @start="start" />
      </div>
    </transition>
  </div>
</template>

<script>
  import { defineComponent } from 'vue'
  import Icon from '@/Components/Icon'
  import Menu from './Menu'

  export default defineComponent({
    props: {
      menus: Array,
      menu: Object,
    },

    components: {
      Icon,
      Menu,
    },

    data() {
      return {
        open: true,
      }
    },
  })
</script>