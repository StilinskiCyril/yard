<template>

    <Head :title="`${company.name} Profile`" />

    <Layout>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="text-left">{{ updateForm.name }} Profile</p>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <div v-if="filterForm.processing" class="spinner-border text-primary"></div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <img alt="Company logo" height="176" width="176" class="rounded-circle" :src="`${updateForm.formattedLogoUrl}`">
                                        <div class="table-responsive mt-3">
                                            <table class="table table-bordered" style="width:100%">
                                                <tr>
                                                    <th>Account Number</th>
                                                    <td>{{ updateForm.accountNo }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Wallet Balance (KES)</th>
                                                    <td>{{ updateForm.walletBalance }}</td>
                                                </tr>
                                                <tr>
                                                    <th>KYC Document</th>
                                                    <td><a :href="updateForm.formattedKycDocUrl">open</a></td>
                                                </tr>
                                                <tr>
                                                    <th>Account Status</th>
                                                    <td>
                                                        <p v-if="updateForm.status === 0" class="text-warning">Inactive</p>
                                                        <p v-if="updateForm.status === 1" class="text-success">Active</p>
                                                        <p v-else class="text-warning">Suspended</p>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="card">
                                    <div class="card-body">
                                        <form>
                                            <div class="form-group row">
                                                <div class="col-md-3">
                                                    <label>Company Name</label>
                                                    <input type="text" class="form-control" v-model="updateForm.name">
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Msisdn/ Phone Number</label>
                                                    <input type="text" class="form-control" v-model="updateForm.msisdn">
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Email Address</label>
                                                    <input type="email" class="form-control" v-model="updateForm.email">
                                                </div>
                                                <div class="col-md-3">
                                                    <label>No Of Employees</label>
                                                    <input type="number" class="form-control" v-model="updateForm.noOfEmployees">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-md-3">
                                                    <label>Address e.g. Nairobi, Kenya</label>
                                                    <input type="text" class="form-control" v-model="updateForm.address">
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Website Url</label>
                                                    <input type="url" class="form-control" v-model="updateForm.websiteUrl">
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Select County</label>
                                                    <select class="form-control" v-model="updateForm.countyId">
                                                        <option v-for="col in counties" :value="col.id">{{ col.name }}</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Upload Logo</label>
                                                    <input type="file" v-on:change="onFileChange($event, 'logo')" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-md-3">
                                                    <label>KYC Upload</label>
                                                    <input type="file" v-on:change="onFileChange($event, 'kyc_doc')" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-md-12">
                                                    <label>About</label>
                                                    <textarea class="form-control" rows="5" v-model="updateForm.about"></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group row mt-3">
                                                <div class="col-md-3">
                                                    <button v-if="!updateForm.processing" @click.prevent="updateRecord()" class="btn btn-primary w-100">Save</button>
                                                    <button v-else type="button" class="btn btn-secondary spinner spinner-dark spinner-right">
                                                        Processing...
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
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
    name: "CompanyProfile",
    props: ['company', 'counties'],
    components: {Layout, Link, Head, Bootstrap5Pagination},
    data() {
        return {
            payloadFromDb: {},
            filterForm: {
                processing: false
            },
            updateForm: {
                uuid: undefined,
                name: undefined,
                msisdn: undefined,
                email: undefined,
                noOfEmployees: undefined,
                address: undefined,
                websiteUrl: undefined,
                countyId: undefined,
                logoUrl: undefined,
                formattedLogoUrl: undefined,
                kycDocUrl: undefined,
                formattedKycDocUrl: undefined,
                about: undefined,
                accountNo: undefined,
                walletBalance: undefined,
                status: undefined,
                processing: false
            }
        }
    },
    mounted() {
        this.loadPayloadFromApi();
    },
    methods: {
        loadPayloadFromApi(){
            this.filterForm.processing = true;
            axios.post(route('companies.load-profile', { company: this.company.uuid}), this.filterForm).then(response => {
                this.payloadFromDb = response.data;
                this.updateForm.uuid = this.payloadFromDb.uuid;
                this.updateForm.name = this.payloadFromDb.name;
                this.updateForm.msisdn = this.payloadFromDb.msisdn;
                this.updateForm.email = this.payloadFromDb.email;
                this.updateForm.noOfEmployees = this.payloadFromDb.no_of_employees;
                this.updateForm.address = this.payloadFromDb.address;
                this.updateForm.websiteUrl = this.payloadFromDb.website_url;
                this.updateForm.countyId = this.payloadFromDb.county_id;
                this.updateForm.formattedLogoUrl = this.payloadFromDb.formatted_logo_url;
                this.updateForm.formattedKycDocUrl = this.payloadFromDb.formatted_kyc_doc_url;
                this.updateForm.about = this.payloadFromDb.about;
                this.updateForm.accountNo = this.payloadFromDb.account_no;
                this.updateForm.walletBalance = this.payloadFromDb.wallet_balance;
                this.updateForm.status = this.payloadFromDb.status;
            }).catch(error => {
                //
            }).finally(() => {
                this.filterForm.processing = false;
            });
        },
        onFileChange(event, type){
            if (type === 'logo'){
                this.updateForm.logoUrl = event.target.files[0];
            } else {
                this.updateForm.kycDocUrl = event.target.files[0];
            }
        },
        updateRecord(){
            let payLoad = new FormData();
            payLoad.append("_method", 'put');
            payLoad.append("name", this.updateForm.name || "");
            payLoad.append("msisdn", this.updateForm.msisdn || "");
            payLoad.append("email", this.updateForm.email || "");
            payLoad.append("noOfEmployees", this.updateForm.noOfEmployees || "");
            payLoad.append("address", this.updateForm.address || "");
            payLoad.append("websiteUrl", this.updateForm.websiteUrl || "");
            payLoad.append("countyId", this.updateForm.countyId || "");
            payLoad.append("logo", this.updateForm.logoUrl ? this.updateForm.logoUrl : '');
            payLoad.append("kycDoc", this.updateForm.kycDocUrl ? this.updateForm.kycDocUrl : '');
            payLoad.append("about", this.updateForm.about || "");

            this.updateForm.processing = true;
            axios.post(route('companies.update-profile', { company: this.updateForm.uuid}), payLoad).then((response) => {
                if (response.data.status){
                    this.loadPayloadFromApi();
                    Swal.fire('Success', response.data.message, 'success');
                    $('#updateRecordModal').modal('hide');
                } else {
                    Swal.fire('Failed', response.data.message, 'warning');
                }
            }).catch((error) => {
                axiosErrorHandler(error);
            }).finally(() => {
                this.updateForm.processing = false;
            });
        }
    }
}
</script>

<style scoped>

</style>
