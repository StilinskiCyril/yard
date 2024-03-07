<template>
    <Header></Header>
    <div class="container-fluid mt-3">
        <div class="row" v-if="$page.props.flash.success || $page.props.flash.error">
            <div class="col-md-12">
                <div :class="`alert ${className} alert-dismissible`">
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    <strong>{{ subText }}</strong> {{ text }}
                </div>
            </div>
        </div>
        <slot></slot>
        <Footer></Footer>
    </div>
</template>

<script>
import Header from './Header.vue';
import Footer from './Footer.vue';
import {Link} from "@inertiajs/vue3";

export default {
    name: "Layout",
    components: {Header, Footer, Link},
    data() {
        return {
            className: undefined,
            text: undefined,
            subText: undefined
        }
    },
    mounted() {
        this.loadAlertClass();
    },
    methods: {
        loadAlertClass(){
            if (this.$page.props.flash.success){
                this.className = 'alert-success';
                this.text = this.$page.props.flash.success;
                this.subText = 'Success!';
            } else {
                this.className = 'alert-warning';
                this.text = this.$page.props.flash.error;
                this.subText = 'Error!';
            }
        },
    }
}
</script>

<style scoped>

</style>
