<template>
  <div class="bg-white">
    <Carousel
      per-page="1"
      navigate-to="someLocalProperty"
      adjust="true"
      autoplay="true"
      autoplayTimeout="1000"
      paginationPosition="bottom-overlay"
      centerMode="true"
      adjustableHeight="true"
    >
      <Slide v-for="(item, index) in carouselItems" :key="index">
        <img :src="item.imageSrc" class="w-full h-full object-cover" />
      </Slide>
    </Carousel>
  </div>
</template>

<script setup>
import { Carousel, Slide } from '@jambonn/vue-concise-carousel'
import '@jambonn/vue-concise-carousel/lib/vue-concise-carousel.css'

const carouselItems = [
  {
    imageSrc:
      'https://content.asos-media.com/-/media/homepages/mw/2023/december/04-uk-homepage/hero/hero-2/2023_homepage_hero-2_mw_1440x640.jpg'
  },
  {
    imageSrc:
      'https://content.asos-media.com/-/media/homepages/mw/2023/december/04-uk-homepage/hero/hero-2/2023_homepage_hero-2_mw_1440x640.jpg'
  },
  {
    imageSrc:
      'https://content.asos-media.com/-/media/homepages/mw/2023/december/04-uk-homepage/hero/hero-2/2023_homepage_hero-2_mw_1440x640.jpg'
  }
]
</script>
