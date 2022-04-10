<template>
  <Link :href="href" :class="`${active && 'border-r-8 border-slate-700 bg-slate-700'} pl-${pl}`" class="flex items-center w-full min-h-[3rem] space-x-1 transition-all">
    <div class="flex-none w-12 h-12 p-2">
      <button class="w-full h-full p-1">
        <Icon :src="icon" class="text-slate-400" />
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