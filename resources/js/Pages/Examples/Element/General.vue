<template>
  <DashboardLayout title="General - Element">
    <div class="flex flex-col space-y-4">
      <Card class="bg-slate-50">
        <template #header>
          <p class="capitalize">pallete pallete</p>
        </template>

        <template #body>
          <div class="flex-wrap p-4">
            <template v-for="(key, i) in Object.keys(colors)" :key="i" class="flex flex-col space-y-1">
              <button v-for="(level, j) in Object.keys(colors[key])" :key="j" :class="`bg-${key}-${level} border border-${key}-${level} px-2 py-1 rounded shadow m-1`" @click.prevent="pushFlashMessage({
                type: 'success',
                text: `bg-${key}-${level}`.toUpperCase(),
                timer: 3000,
              })">
                {{ key }}-{{ level }}
              </button>
            </template>
          </div>
        </template>
      </Card>

      <div class="grid grid-cols-3 gap-2">
        <Card class="bg-slate-50">
          <template #header>
            <p class="capitalize">split button</p>
          </template>

          <template #body>
            <div class="flex flex-col space-y-2 items-center p-4">
              <div class="flex items-center space-x-2">
                <BtnSplit>
                  <template #text>Action</template>

                  <template #actions>
                    <p class="px-2">one</p>
                    <p class="px-2">two</p>
                    <p class="px-2">three</p>
                  </template>
                </BtnSplit>
              </div>
            </div>
          </template>
        </Card>
      </div>
    </div>
  </DashboardLayout>
</template>

<script>
  import { defineComponent } from 'vue'
  import * as tailwind from 'tailwindcss/colors'
  import DashboardLayout from '@/Layouts/DashboardLayout'
  import Card from '@/Components/Card'
  import BtnSplit from '@/Components/Button/Split'

  export default defineComponent({
    components: {
      DashboardLayout,
      Card,
      BtnSplit,
    },

    data() {
      const colors = {}

      for (const key in tailwind) {
        const color = tailwind[key]

        if (typeof color === 'object') {
          colors[key] = color
        }
      }

      return {
        colors,
      }
    },

    methods: {
      min(level) {
        if (Number(level) == 50) {
          return level
        }

        if (Number(level) == 100) {
          return 50
        }

        return Number(level) - 100
      },
    },
  })
</script>