<script>
import Layout from './../Layout.vue';
import {Link, Head} from "@inertiajs/vue3";

export default {
    name: "AdminDashboard",
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

        <div class="row mt-3">
            <div class="col-md-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="float-end mt-2"><i class="fas fa-calendar-alt" aria-hidden="true"></i></div>
                        <div><h4 class="mb-1 mt-1"><span>0</span></h4>
                            <p class="text-muted mb-0">Total Vehicles</p></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="float-end mt-2"><i class="fas fa-calendar-alt" aria-hidden="true"></i></div>
                        <div><h4 class="mb-1 mt-1"><span>0</span></h4>
                            <p class="text-muted mb-0">Wallet Balance</p></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="float-end mt-2"><i class="fas fa-calendar-alt" aria-hidden="true"></i></div>
                        <div><h4 class="mb-1 mt-1"><span>0</span></h4>
                            <p class="text-muted mb-0">Total Companies</p></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="float-end mt-2"><i class="fas fa-calendar-alt" aria-hidden="true"></i></div>
                        <div><h4 class="mb-1 mt-1"><span>0</span></h4>
                            <p class="text-muted mb-0">Total Users</p></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-8 col-sm-8 col-md-8 col-lg-8 col-xl-8 col-xxl-8">
                <div class="card flex-fill w-100">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Order Payments</h5>
                    </div>
                    <div class="card-body py-3">
                        <div class="align-self-center">
                            <p>Graph here</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-4 col-sm-4 col-md-4 col-lg-4 col-xl-4 col-xxl-4">
                <div class="card flex-fill w-100">
                    <div class="card-header">
                        <h5 class="card-title mb-0">More Analytics</h5>
                    </div>
                    <div class="card-body py-3">
                        <div class="align-self-center">
                            <p>Another graph here</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Layout>
</template>

<style scoped>

</style>
