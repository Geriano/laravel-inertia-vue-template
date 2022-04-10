<template>
  <button :class="`border border-slate-300 rounded hover:bg-slate-100 ${$props.class}`">
    <div class="flex items-center space-x-2 pl-2">
      <div>
        <slot name="text" />
      </div>
      
      <div @click.prevent="open = ! open" class="relative flex-none border-l w-8 h-8 p-1">
        <Icon src="caret-down" class="text-white" />

        <transition name="fade">
          <slot v-if="open" name="actions" />
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

    props: {
      r: {
        type: Number,
        default: null,
      },
      g: {
        type: Number,
        default: null,
      },
      b: {
        type: Number,
        default: null,
      },
      class: String,
    },

    data() {
      return {
        open: false,
      }
    },
  })
</script>