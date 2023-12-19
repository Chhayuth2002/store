<template>
  <div class="flex py-6 border-b">
    <div class="relative h-24 w-24 rounded-md overflow-hidden sm:h-48 sm:w-48">
      <img :src="product.imageSrc" alt="" class="object-cover object-center w-full h-full" />
    </div>
    <div className="relative ml-4 flex flex-1 flex-col justify-between sm:ml-6">
      <div className="absolute z-10 right-0 top-0">
        <Button variant="link"><X size="15" /> </Button>
      </div>
      <div className="relative pr-9 ">
        <p class="font-semibold text-xl">{{ product.price }}</p>
        <div className="flex justify-between mt-1">
          <p className="text-base font-semibold text-neutral-700">{{ product.name }}</p>
        </div>
        <div className="mt-3 flex text-sm">
          <p className="text-gray-500">Green</p>
          <p className="text-gray-500 ml-4 border-l border-gray-200 pl-4 mr-3">XL</p>
          <p className="text-gray-500 ml-4 border-l border-gray-200 pl-4 mr-3">Qty:</p>
          <div class="-mt-3 mr-35">
            <Dropdown />
          </div>
        </div>
        <!-- <div class="flex mt-5">
          <Button><HeartIcon class="w-4 h-4 mr-2" /> Save for later</Button>
        </div> -->
      </div>
    </div>
  </div>
</template>

<script setup>
import { X } from 'lucide-vue-next'
import Dropdown from './Dropdown.vue'
import { Button } from './ui/button'

// import { IconButton } from 'lucide-vue-next'
import { useCartStore } from '@/stores/use-cart'
import { HeartIcon } from 'lucide-vue-next'

defineProps({
  product: Array
})

const cart = useCartStore()

const onRemove = (id) => {
  cart.removeItem(id)
}
</script>
