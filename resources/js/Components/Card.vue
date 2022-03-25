<template>
  <div class="border border-slate-300 rounded-md shadow">
    <div v-if="$slots.header" class="border-b border-slate-300 p-2 ">
      <slot name="header" />
    </div>

    <div v-if="$slots.body" class="p-2">
      <slot name="body" />
    </div>

    <div v-if="$slots.footer" class="border-t border-slate-300 p-2 ">
      <slot name="footer" />
    </div>
  </div>
</template>