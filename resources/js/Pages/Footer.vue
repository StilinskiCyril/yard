<template>
    <div class="mt-3">
        <h6 class="text-center">&copy {{ currentYear }} | {{ $page.props.appName }} | All Rights Reserved </h6>
    </div>
</template>

<script>
import {Link} from "@inertiajs/vue3";

export default {
    name: "Footer",
    components: {Link},
    data() {
        return {
            currentYear: undefined
        };
    },
    mounted() {
        this.getCurrentYear();
    },
    methods: {
        getCurrentYear() {
            const currentDate = new Date();
            this.currentYear = currentDate.getFullYear();
        }
    }
}
</script>

<style scoped>

</style>
