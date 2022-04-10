<template>
  <div class="grid sm:grid-cols-2 md:grid-cols-4 gap-2">
    <div class="flex flex-col items-center justify-center w-full h-full bg-white border rounded-md shadow-md p-4">
      <p class="text-md font-semibold capitalize">{{ __('user') }}</p>
      <p class="text-5xl font-bold uppercase">{{ count.all }}</p>
    </div>

    <div class="flex flex-col items-center justify-center w-full h-full bg-white border rounded-md shadow-md p-4">
      <p class="text-md font-semibold capitalize">{{ __('active') }}</p>
      <p class="text-5xl font-bold uppercase">{{ count.active }}</p>
    </div>

    <div class="flex flex-col items-center justify-center w-full h-full bg-white border rounded-md shadow-md p-4">
      <p class="text-md font-semibold capitalize">{{ __('inactive') }}</p>
      <p class="text-5xl font-bold uppercase">{{ count.inactive }}</p>
    </div>

    <div class="flex flex-col items-center justify-center w-full h-full bg-white border rounded-md shadow-md p-4">
      <p class="text-md font-semibold capitalize">{{ __('deleted') }}</p>
      <p class="text-5xl font-bold uppercase">{{ count.deleted }}</p>
    </div>
  </div>  
</template>

<script>
  import { defineComponent } from "vue";

  export default defineComponent({
    props: {
      count: Object,
    }
  })
</script>