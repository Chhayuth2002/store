<template title="Welcome">
    <AppLayout>
        <PromoSlide />
        <TrendingSec />
        {{ categories }}
    </AppLayout>
</template>

<script setup>
import PromoSlide from "@/Components/PromoSlide.vue";
import TrendingSec from "@/Components/TrendingSec.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
// import { defineComponent } from "vue";

// export default defineComponent({
//     components: {
//         PromoSlide,
//         TrendingSec,
//         AppLayout,
//     },
//     props: ["products", "categories"],
// });
defineProps({
    products: Object,
    categories: Object,
});
</script>
