<template>
  <Link :href="href" :class="active && 'bg-slate-700 border-r-8 border-slate-900 shadow-md'" class="flex items-center w-full min-h-[3rem] space-x-1 transition-all hover:bg-slate-700 hover:shadow-lg">
    <div class="flex-none w-12 h-12 p-2">
      <button class="w-full h-full p-1">
        <Icon :src="icon" r=156 g=165 b=173 class="w-full h-full" />
      </button>
    </div>

    <span class="font-semibold w-full text-gray-400 capitalize">
      <slot />
    </span>
  </Link>
</template>

<script>
  import { defineComponent } from "vue";
  import { Link } from '@inertiajs/inertia-vue3';
  import Icon from '@/Components/Icon';

  export default defineComponent({
    components: {
      Link,
      Icon,
    },

    props: {
      href: String,
      icon: String,
      active: {
        type: Boolean,
        default: false,
      },
    },
  })
</script>