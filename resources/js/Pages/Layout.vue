<script>
import Header from './Header.vue';
import Footer from './Footer.vue';
import {Link} from "@inertiajs/vue3";

export default {
    name: "Layout",
    components: {Header, Footer, Link},
    //
}
</script>

<template>
    <Header></Header>
    <div class="container-fluid mt-3">
        <slot></slot>
        <Footer></Footer>
    </div>
</template>

<style scoped>

</style>
