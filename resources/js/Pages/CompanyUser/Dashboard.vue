<script>
import Layout from './../Layout.vue';
import {Link, Head} from "@inertiajs/vue3";

export default {
    name: "CompanyUserDashboard",
    props: ['greetings'],
    components: {Layout, Link, Head},
    //
}
</script>

<template>
    <Head title="Admin Dashboard" />
    <Layout>
        <div class="row">
            <div class="col-md-12">
                <div class="card bg-primary">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-sm-8 text-left"><p class="text-white font-size-18">{{ greetings }}
                                <b>{{ $page.props.userName}}</b>!</p>
                                <div class="mt-3"><h6 class="text-white"> Welcome to {{ $page.props.appName}}</h6></div>
                            </div>
                            <div class="col-sm-4 text-md-end">
                                <div class="mt-4 mt-sm-0">
                                    <img :src="'/images/setup-analytics-amico.svg'" class="img-fluid" alt="" height="125" width="85">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Layout>
</template>

<style scoped>

</style>
