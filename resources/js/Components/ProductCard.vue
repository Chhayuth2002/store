<template>
  <div :class="style">
    <router-link to="product/product-test-1" class="group block overflow-hidden">
      <div class="relative h-[250px] sm:h-[250px]">
        <img :src="product.imageSrc" alt="" class="absolute inset-0 h-full w-full object-cover" />
      </div>

      <div class="relative bg-white pt-3">
        <h3 class="text-sm text-gray-700 group-hover:underline group-hover:underline-offset-4">
          {{ product.name }}
        </h3>

        <div class="mt-1.5 flex items-center justify-between text-gray-900">
          <p class="tracking-wide">{{ product.price }}</p>

          <!-- <p class="text-xs uppercase tracking-wide">aaa</p> -->
        </div>
      </div>
    </router-link>
  </div>
</template>

<script setup>
defineProps({
  product: Array
})
</script>
