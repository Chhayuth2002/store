<template>
    <Link :href="href" :class="class" v-if="as==='href'"></link>
</template>

<script setup>
import { Link} from "@inertiajs/vue3";
import { defineProps } from 'vue';
defineProps({
    active,
    href,
    as
})
</script>
