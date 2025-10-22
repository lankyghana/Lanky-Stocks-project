import { createApp } from 'vue';

const ShopGrid = {
  name: 'ShopGrid',
  props: {
    products: {
      type: Array,
      default: () => [],
    },
  },
  methods: {
    currency(n) {
      // Simple currency format for NGN
      const num = Number(n ?? 0);
      return new Intl.NumberFormat('en-NG', { style: 'currency', currency: 'NGN' }).format(num);
    },
  },
  template: `
    <div>
      <div v-if="!products.length" class="text-gray-500">No products found.</div>
      <div v-else class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        <div v-for="p in products" :key="p.id" class="bg-white rounded shadow p-3 flex flex-col">
          <img :src="p.image_url" alt="" class="h-40 w-full object-cover rounded">
          <div class="mt-3 font-semibold">{{ p.name }}</div>
          <div class="text-sm text-gray-600 line-clamp-2" v-html="p.description"></div>
          <div class="mt-2 text-sm">
            <span class="px-2 py-1 rounded bg-gray-100">{{ (p.type || '').toUpperCase() }}</span>
          </div>
          <div class="mt-3 font-bold">{{ currency(p.price) }}</div>
          <button class="mt-auto btn btn-primary disabled:opacity-50" disabled>Buy (coming soon)</button>
        </div>
      </div>
    </div>
  `,
};

const el = document.getElementById('shop-app');
if (el) {
  createApp({})
    .component('shop-grid', ShopGrid)
    .mount('#shop-app');
}
