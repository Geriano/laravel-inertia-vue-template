<template>
  <button :class="`border border-slate-300 rounded hover:bg-slate-100 ${$props.class}`">
    <div class="flex items-center space-x-2 pl-2">
      <div>
        <slot name="text" />
      </div>
      
      <div @click.prevent="open = ! open" class="relative flex-none border-l w-8 h-8 p-2">
        <Icon src="caret-down" class="w-full h-full" r="51" g="65" b="85" />

        <transition name="fade">
          <div v-if="open" class="absolute top-[102.5%] left-0 flex flex-col bg-slate-50 border border-slate-200 rounded-md py-2 shadow-md">
            <slot name="actions" />
          </div>
        </transition>
      </div>
    </div>
  </button>
</template>

<script>
  import { defineComponent } from 'vue'
  import Icon from '@/Components/Icon'

  export default defineComponent({
    components: {
      Icon,
    },

    data() {
      return {
        open: false,
      }
    },
  })
</script>