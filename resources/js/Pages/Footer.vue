<script>
import {Link} from "@inertiajs/vue3";

export default {
    name: "Footer",
    components: {Link}
}
</script>

<template>
    <footer class="footer">
        <div class="container-fluid">
            <div class="row text-muted">
                <div class="col-8 text-left">
                    <ul class="list-inline">
                        <li class="list-inline-item">
                            <a class="text-muted" href="" target="_blank">Faq</a>
                        </li>
                        <li class="list-inline-item">
                            <a class="text-muted" href="" target="_blank">Terms and Conditions </a>
                        </li>
                    </ul>
                </div>
                <div class="col-4 text-right">
                    <p class="mb-0">
                        &copy; 2024 Powered By <a href="https://github.com/StilinskiCyril" target="_blank" class="text-muted">StilinskiTech</a>
                    </p>
                </div>
            </div>
        </div>
    </footer>

</template>

<style scoped>

</style>
