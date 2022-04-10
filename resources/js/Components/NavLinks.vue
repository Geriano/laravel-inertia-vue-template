<template>
  <div class="relative flex flex-col w-full h-auto" style="z-index: 2;">
    <button @click.prevent="showingDropdown = ! showingDropdown" :class="`${showingDropdown && 'border-r-8 border-slate-700 bg-slate-700'} pl-${$props.pl}`" class="flex-none flex items-center w-full min-h-[3rem] space-x-1 transition-all">
      <div class="flex-none w-12 h-12 p-2">
        <button class="w-full h-full p-1">
          <Icon :src="icon" class="text-slate-400" />
        </button>
      </div>

      <span class="font-semibold w-full text-left text-gray-400 capitalize">
        {{ title }}
      </span>

      <div class="flex-none w-10 h-10 p-2">
        <Icon :src="showingDropdown ? 'caret-down' : 'caret-left'" class="text-slate-400 w-full h-full" />
      </div>
    </button>

    <transition-group name="fade">
      <div v-if="showingDropdown" class="flex flex-col w-full h-auto">
        <slot />
      </div>
    </transition-group>
  </div>
</template>

<style scoped>
  .slide-enter-active,
  .slide-leave-active {
    transition: all 0.25s ease;
  }

  .slide-enter-from,
  .slide-leave-to {
    transform: translateY(-3rem);
  }

  .slide-enter-to,
  .slide-leave-from {
    transform: translateY(0);
  }
</style>

<script>
  import { defineComponent } from 'vue';
  import Icon from '@/Components/Icon';
  
  export default defineComponent({
    data() {
      return {
        showingDropdown: this.$props.active,
      };
    },

    components: {
      Icon,
    },

    props: {
      title: String,
      icon: String,
      active: {
        type: Boolean,
        default: false,
      },
      child: {
        type: Boolean,
        default: false,
      },
      pl: {
        type: Number,
      },
    },
  })
</script>