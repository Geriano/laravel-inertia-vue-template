<template>
  <button :class="`border border-slate-300 rounded hover:bg-slate-100 ${$props.class}`">
    <div class="flex items-center space-x-2 pl-2">
      <div>
        <slot name="text" />
      </div>
      
      <div @click.prevent="open = ! open" class="relative flex-none border-l w-8 h-8 p-2">
        <Icon src="caret-down" class="w-full h-full" :r="$props.r === null ? 51 : $props.r" :g="$props.g === null ? 65 : $props.g" :b="$props.b === null ? 85 : $props.b" />

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