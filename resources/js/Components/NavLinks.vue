<template>
  <div class="relative flex flex-col w-full h-auto" style="z-index: 2;">
    <button @click.prevent="showingDropdown = ! showingDropdown" :class="(showingDropdown && 'bg-slate-700') + (child && ' pl-4')" class="flex-none flex items-center w-full min-h-[3rem] space-x-1 transition-all hover:bg-slate-700 hover:shadow-lg">
      <div class="flex-none w-12 h-12 p-2">
        <button class="w-full h-full p-1">
          <Icon :src="icon" r=156 g=165 b=173 class="w-full h-full" />
        </button>
      </div>

      <span class="font-semibold w-full text-left text-gray-400 capitalize">
        {{ title }}
      </span>
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
        showingDropdown: this.$props.open,
      };
    },

    components: {
      Icon,
    },

    props: {
      title: String,
      icon: String,
      open: {
        type: Boolean,
        default: false,
      },
      child: {
        type: Boolean,
        default: false,
      },
    },
  })
</script>