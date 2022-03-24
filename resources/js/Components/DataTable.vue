<template>
  <transition>
    <div v-if="data?.data" class="bg-slate-100 p-4 rounded-md">
      <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2 sm:items-center justify-between mb-2">
        <div class="flex w-full sm:w-auto items-center space-x-2 justify-between sm:justify-start">
          <span class="lowercase first-letter:capitalize">{{ __('per page') }}</span>

          <select @change.prevent="perPage = Number($event.target.value); fetch()" :value="perPage" class="bg-transparent border border-slate-300 rounded-md p-1">
            <option value="10">10</option>
            <option value="10">15</option>
            <option value="10">25</option>
            <option value="10">50</option>
            <option value="10">100</option>
            <option value="10">500</option>
          </select>
        </div>

        <input type="search" @input.prevent="search = $event.target.value.trim(); fetch()" :value="search" class="bg-transparent sm:w-2/5 border border-slate-300 rounded-md placeholder:capitalize text-xs" :placeholder="__('search something')">
      </div>

      <div class="overflow-auto border border-slate-300 rounded-md mb-2 max-h-[25rem]">
        <table class="w-full border-collapse">
          <thead class="bg-slate-200 sticky top-0 z-10">
            <slot name="head" :table="table" :sort="sort" />
          </thead>

          <tfoot class="bg-slate-200 sticky bottom-0">
            <slot name="foot" :table="table" :sort="sort" />
          </tfoot>

          <tbody>
            <slot name="body" v-for="(item, i) in data.data" :key="i" :item="item" :i="i" />
          </tbody>
        </table>
      </div>

      <div class="flex items-center">
        <div class="flex-none w-1/4">
          <p class="lowercase first-letter:capitalize text-xs">
            {{ __('displaying') }} {{ data.from }} {{ __('to') }} {{ data.to }} {{ __('from') }} {{ data.total }}
          </p>
        </div>

        <div class="flex flex-wrap w-full items-center justify-end">
          <div
            v-for="(link, i) in data.links"
            :key="i"
            v-html="__(link.label)"
            :class="(i == 0 && 'rounded-l-md') || (i == data.links.length - 1 && 'rounded-r-md') || (link.active && 'bg-slate-700 border-slate-600 text-slate-100')"
            @click.prevent="page(link)"
            class="text-xs border p-1 shadow cursor-pointer"></div>
        </div>
      </div>
    </div>

    <div v-else class="bg-slate-100 p-4 rounded-md h-screen max-h-96">
      Loading...
    </div>
  </transition>
</template>

<script>
  import { defineComponent } from 'vue'

  export default defineComponent({
    props: {
      route: {
        type: String,
        required: true,
      },
    },

    data() {
      return {
        url: new String,
        perPage: 10,
        search: new String,

        data: {},

        table: {
          sort: {
            key: new String,
            order: new String,
          },
        },
      }
    },

    methods: {
      fetch() {
        const data = {}

        if (this.perPage) data.perPage = this.perPage;
        if (this.search.trim()) data.search = this.search;
        if (this.table.sort.key.trim() && this.table.sort.order.trim()) data.sort = {key: this.table.sort.key, order: this.table.sort.order};

        return axios.post(this.url, data)
                    .then(response => response.data)
                    .then(data => this.data = data)
      },

      sort(key) {
        if (this.table.sort.key === key) {
          this.table.sort.order = this.table.sort.order === 'asc' ? 'desc' : 'asc'
        } else {
          this.table.sort.key = key
          this.table.sort.order = 'asc'
        }

        this.fetch()
      },

      page(link) {
        if (link.url) {
          this.url = link.url
          this.fetch()
        }
      },
    },

    mounted() {
      this.url = route(this.route)
      return setTimeout(() => this.fetch(), 0)
    },
  })
</script>