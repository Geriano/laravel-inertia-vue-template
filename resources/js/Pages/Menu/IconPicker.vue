<template>
  <div class="fixed top-0 left-0 w-full h-screen flex items-center justify-center z-10">
    <div class="flex flex-col space-y-2 w-full max-w-6xl bg-white rounded-md">
      <div class="flex-none w-full bg-slate-200 rounded-t-md flex items-center justify-between p-2">
        <p class="lowercase first-letter:capitalize font-semibold text-slate-800">
          {{ __('select icon')}}
        </p>

        <div class="flex items-center space-x-2">
          <Icon @click.prevent="$emit('close')" src="times" class="border border-slate-700 rounded-md px-1" />
        </div>
      </div>

      <div class="grid grid-cols-12 gap-1 max-h-96 overflow-auto p-4">
        <Icon v-for="(icon, i) in $page.props.icons" :key="i" class="text-5xl text-center py-2 text-slate-700 rounded-md cursor-pointer hover:bg-slate-700 hover:text-white" :src="icon" @click.prevent="$emit('change', icon)" />
      </div>
    </div>
  </div>
</template>

<script setup>
import Icon from '@/Components/Icon'
</script>