<template>

    <Head :title="`${company.name} Wallet`" />

    <Layout>

        <div class="row">
            <div class="col-lg-10 col-xl-10 col-md-10 col-sm-10 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="text-left">{{ company.name }}'s Wallet Transaction History</p>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <button @click.prevent="showTopUpWalletModal()" class="btn btn-primary btn-sm"><i class="fas fa-credit-card"></i>
                                    Top Up Wallet
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form>
                            <div class="form-group row">
                                <div class="col-md-2">
                                    <input v-model="filterForm.keyword" type="text" class="form-control" placeholder="Enter keyword e.g. account number, msisdn, name">
                                </div>
                                <div class="col-md-2">
                                    <input v-model="filterForm.start" type="date" class="form-control" placeholder="Start">
                                </div>
                                <div class="col-md-2">
                                    <input v-model="filterForm.end" type="date" class="form-control" placeholder="End">
                                </div>
                                <div class="col-md-2">
                                    <button v-if="filterForm.processing" class="btn btn-secondary w-100 spinner spinner-dark spinner-right">
                                        Processing...
                                    </button>
                                    <button v-else @click.prevent="loadPayloadFromApi()" class="btn btn-outline-primary w-100"><i class="fas fa-search"></i> Search</button>
                                </div>
                                <div class="col-md-2">
                                    <button v-on:click.prevent="clearFilter()" class="btn btn-outline-primary w-100"><i class="fas fa-brush"></i> Clear Filter</button>
                                </div>
                            </div>
                        </form>
                        <div class="table-responsive mt-3">
                            <table class="table table-responsive table-bordered" style="width:100%">
                                <thead>
                                <tr>
                                    <th>Amount (KES)</th>
                                    <th>Type</th>
                                    <th>Notes</th>
                                    <th>Date</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="col in payloadFromDb.data" :key="col.uuid">
                                    <td>
                                        <p>{{ col.amount }}</p>
                                    </td>
                                    <td>
                                        <p>{{ col.type }}</p>
                                    </td>
                                    <td>
                                        <p>{{ col.notes }}</p>
                                    </td>
                                    <td>
                                        <p>{{ col.created_at }}</p>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <Bootstrap5Pagination @limit=20 :data="payloadFromDb" @pagination-change-page="loadPayloadFromApi"/>
                        <div class="text-center">Showing Page {{ payloadFromDb.current_page }} Of {{ payloadFromDb.last_page }}</div>
                        <div class="text-center" v-if="payloadFromDb.total < 1">
                            <h6 class="text-danger">No results found</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Up Wallet Modal -->
        <div class="modal fade" id="topUpWalletModal">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Top Up Wallet</h4>
                    </div>
                    <div class="modal-body">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="card">
                                            <div class="card-header">
                                                Top Up Via M-pesa
                                            </div>
                                            <div class="card-body">
                                                <div class="card-title text-center">
                                                    <img :src="`/images/mpesa.png`" alt="M-pesa logo" height="120" width="200">
                                                </div>
                                                <form>
                                                    <div class="form-group row">
                                                        <div class="col-md-12">
                                                            <input v-model="topUpWalletForm.msisdn" placeholder="Enter msisdn/ phone number" type="text" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row mt-3">
                                                        <div class="col-md-12">
                                                            <input v-model="topUpWalletForm.amount" placeholder="Enter amount (KES)" type="number" min="1" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row mt-3">
                                                        <div class="col-md-12">
                                                            <button v-if="!topUpWalletForm.processing" @click.prevent="topUpViaMpesa()" class="btn btn-primary w-100">Top Up</button>
                                                            <button v-else type="button" class="btn btn-secondary w-100 spinner spinner-dark spinner-right">
                                                                Processing...
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card">
                                            <div class="card-header">
                                                How To Top Up Directly From M-pesa Menu
                                            </div>
                                            <div class="card-body">
                                                <ul>
                                                    <li>Go to M-pesa menu</li>
                                                    <li>Lipa na M-pesa</li>
                                                    <li>Paybill</li>
                                                    <li>Enter business number: XXXXXX</li>
                                                    <li>Enter account number: {{ company.account_no }}</li>
                                                    <li>Enter amount</li>
                                                    <li>Enter PIN</li>
                                                    <li>Confirm</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card">
                                            <div class="card-header">
                                                How To Top Up From Bank
                                            </div>
                                            <div class="card-body">
                                                <div class="card-title text-center">
                                                    <img :src="`/images/ncba.png`" alt="M-pesa logo" height="70" width="200">
                                                </div>
                                                <div class="auto-buzz-note p-3">
                                                    <p>We accept bank deposits & cheque payments using the following NCBA Bank (KE) account.</p>
                                                    <ul>
                                                        <li>Bank Name: NCBA</li>
                                                        <li>Account Name: AutoBuzz</li>
                                                        <li>Branch: XXXXXX</li>
                                                        <li >Account Number: XXXXXX</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

    </Layout>

</template>

<script>
import {Link, Head} from "@inertiajs/vue3";
import Swal from "sweetalert2";
import { Bootstrap5Pagination } from 'laravel-vue-pagination';
import Layout from "../Layout.vue";
import { axiosErrorHandler } from '../../axiosErrorHandler.js';

export default {
    name: "CompanyWallet",
    props: ['company'],
    components: {Layout, Link, Head, Bootstrap5Pagination},
    data() {
        return {
            payloadFromDb: {},
            filterForm: {
                sortBy : 'latest',
                paginate: true,
                keyword: undefined,
                start: undefined,
                end: undefined,
                processing: false
            },
            topUpWalletForm: {
                msisdn: undefined,
                amount: undefined,
                processing: false
            }
        }
    },
    mounted() {
        this.loadPayloadFromApi();
        this.topUpWalletForm.msisdn = this.company.msisdn;
    },
    methods: {
        loadPayloadFromApi(page = 1){
            this.filterForm.processing = true;
            axios.post(route('companies.load-wallet-transactions', {page: page, company: this.company.uuid}), this.filterForm).then(response => {
                this.payloadFromDb = response.data;
            }).catch(error => {
                //
            }).finally(() => {
                this.filterForm.processing = false;
            });
        },
        showTopUpWalletModal(){
            $('#topUpWalletModal').modal('show');
        },
        topUpViaMpesa(){
            Swal.fire('Info', 'Coming soon...', 'warning');
        },
        clearFilter(){
            this.filterForm.keyword = undefined;
            this.loadPayloadFromApi();
        }
    }
}
</script>

<style scoped>
.auto-buzz-note {
    border-left: 6px solid #4CAF50;
    background-color: #FAFAFA;
    color: #616161;
}
</style>
