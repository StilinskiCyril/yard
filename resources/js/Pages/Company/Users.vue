<template>

    <Head :title="`${company.name} Users`" />

    <Layout>

        <div class="row">
            <div class="col-lg-10 col-xl-10 col-md-10 col-sm-10 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="text-left">Manage {{ company.name }} Users</p>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <button @click.prevent="showCreateRecordModal()" class="btn btn-primary btn-sm"><i class="fas fa-circle-plus"></i>
                                    Create User
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form>
                            <div class="form-group row">
                                <div class="col-md-2">
                                    <input v-model="filterForm.name" type="text" class="form-control" placeholder="Enter name">
                                </div>
                                <div class="col-md-2">
                                    <input v-model="filterForm.msisdn" type="text" class="form-control" placeholder="Enter msisdn/ phone number">
                                </div>
                                <div class="col-md-2">
                                    <input v-model="filterForm.email" type="email" class="form-control" placeholder="Enter email">
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
                                    <th>Name</th>
                                    <th>Msisdn/ Phone Number</th>
                                    <th>Email</th>
                                    <th>Company Position</th>
                                    <th>Account Status</th>
                                    <th>2-FA</th>
                                    <th>Roles</th>
                                    <th colspan="3">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="col in payloadFromDb.data" :key="col.uuid">
                                    <td><p>{{ col.name }}</p></td>
                                    <td><p>{{ col.msisdn }}</p></td>
                                    <td><p>{{ col.email }}</p></td>
                                    <td><p>{{ col.company_position }}</p></td>
                                    <td>
                                        <p v-if="col.status === 0" class="text-warning">Inactive</p>
                                        <p v-else-if="col.status === 1" class="text-success">Active</p>
                                        <p v-else class="text-warning">Suspended</p>
                                    </td>
                                    <td>
                                        <p v-if="col.two_factor_auth === 0" class="text-warning">Disabled</p>
                                        <p v-else class="text-success">Enabled</p>
                                    </td>
                                    <td>
                                        <p v-for="role in col.roles" :key="role">{{ role }}</p>
                                    </td>
                                    <td>
                                        <button @click.prevent="showUpdateRecordModal(col)" class="btn btn-outline-primary btn-sm"><i class="fas fa-edit"></i> update</button>
                                    </td>
                                    <td>
                                        <button class="btn btn-outline-primary btn-sm"><i class="fas fa-user-shield"></i> roles</button>
                                    </td>
                                    <td>
                                        <button @click.prevent="removeFromCompany(col)" class="btn btn-outline-danger btn-sm"><i class="fas fa-ban"></i> remove</button>
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

        <!-- Create Record Modal -->
        <div class="modal fade" id="createRecordModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Create {{ company.name }} User</h4>
                    </div>
                    <div class="modal-body">
                        <div class="card">
                            <div class="card-body">
                                <form>
                                    <div class="form-group row">
                                        <label class="col-md-2">Name</label>
                                        <div class="col-md-10">
                                            <input v-model="createForm.name" type="text" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row mt-3">
                                        <label class="col-md-2">Msisdn/ Phone Number</label>
                                        <div class="col-md-10">
                                            <input v-model="createForm.msisdn" type="text" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row mt-3">
                                        <label class="col-md-2">Email Address</label>
                                        <div class="col-md-10">
                                            <input v-model="createForm.email" type="email" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row mt-3">
                                        <label class="col-md-2">Company Position e.g. HR, CEO</label>
                                        <div class="col-md-10">
                                            <input v-model="createForm.companyPosition" type="text" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row mt-3">
                                        <label class="col-md-2">2-FA</label>
                                        <div class="col-md-10">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="mySwitch" v-model="createForm.twoFactorAuth">
                                                <label class="form-check-label" for="mySwitch">{{ createForm.twoFactorAuth ? 'ENABLED' : 'DISABLED' }}</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row mt-3">
                                        <label class="col-md-2">Password</label>
                                        <div class="col-md-10">
                                            <input v-model="createForm.password" type="password" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row mt-3">
                                        <label class="col-md-2">Confirm Password</label>
                                        <div class="col-md-10">
                                            <input v-model="createForm.password_confirmation" type="password" class="form-control">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Nevermind</button>
                        <button v-if="!createForm.processing" @click.prevent="createRecord()" class="btn btn-primary">Confirm</button>
                        <button v-else type="button" class="btn btn-secondary spinner spinner-dark spinner-right">
                            Processing...
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Update Record Modal -->
        <div class="modal fade" id="updateRecordModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Update User</h4>
                    </div>
                    <div class="modal-body">
                        <div class="card">
                            <div class="card-body">
                                <form>
                                    <div class="form-group row">
                                        <label class="col-md-2">Name</label>
                                        <div class="col-md-10">
                                            <input v-model="updateForm.name" type="text" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row mt-3">
                                        <label class="col-md-2">Msisdn/ Phone Number</label>
                                        <div class="col-md-10">
                                            <input v-model="updateForm.msisdn" type="text" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row mt-3">
                                        <label class="col-md-2">Email Address</label>
                                        <div class="col-md-10">
                                            <input v-model="updateForm.email" type="email" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row mt-3">
                                        <label class="col-md-2">Company Position e.g. HR, CEO</label>
                                        <div class="col-md-10">
                                            <input v-model="updateForm.companyPosition" type="text" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row mt-3">
                                        <label class="col-md-2">2-FA</label>
                                        <div class="col-md-10">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="mySwitch" v-model="updateForm.twoFactorAuth">
                                                <label class="form-check-label" for="mySwitch">{{ updateForm.twoFactorAuth ? 'ENABLED' : 'DISABLED' }}</label>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Nevermind</button>
                                <button v-if="!updateForm.processing" @click.prevent="updateRecord()" class="btn btn-primary">Confirm</button>
                                <button v-else type="button" class="btn btn-secondary spinner spinner-dark spinner-right">
                                    Processing...
                                </button>
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
    name: "CompanyUsers",
    props: ['company'],
    components: {Layout, Link, Head, Bootstrap5Pagination},
    data() {
        return {
            payloadFromDb: {},
            filterForm: {
                sort_by : 'latest',
                paginate: true,
                name: undefined,
                msisdn: undefined,
                email: undefined,
                processing: false
            },
            createForm: {
                name: undefined,
                msisdn: undefined,
                email: undefined,
                companyPosition: undefined,
                twoFactorAuth: undefined,
                password: undefined,
                password_confirmation: undefined,
                processing: false
            },
            updateForm: {
                uuid: undefined,
                name: undefined,
                msisdn: undefined,
                email: undefined,
                companyPosition: undefined,
                twoFactorAuth: undefined,
                processing: false
            }
        }
    },
    mounted() {
        this.loadPayloadFromApi();
    },
    methods: {
        loadPayloadFromApi(page = 1){
            this.filterForm.processing = true;
            axios.post(route('companies.load-users', {page: page, company: this.company.uuid}), this.filterForm).then(response => {
                this.payloadFromDb = response.data;
            }).catch(error => {
                //
            }).finally(() => {
                this.filterForm.processing = false;
            });
        },
        showCreateRecordModal(){
            $('#createRecordModal').modal('show');
        },
        createRecord(){
            this.createForm.processing = true;
            axios.post(route('companies.create-user', {company: this.company.uuid}), this.createForm).then((response) => {
                if (response.data.status){
                    this.loadPayloadFromApi();
                    this.createForm.name = undefined;
                    Swal.fire('Success', response.data.message, 'success');
                    $('#createRecordModal').modal('hide');
                } else {
                    Swal.fire('Failed', response.data.message, 'warning');
                }
            }).catch((error) => {
                axiosErrorHandler(error);
            }).finally(() => {
                this.createForm.processing = false;
            });
        },
        showUpdateRecordModal(col){
            this.updateForm.uuid = col.uuid;
            this.updateForm.name = col.name;
            this.updateForm.msisdn = col.msisdn;
            this.updateForm.email = col.email;
            this.updateForm.companyPosition = col.company_position;
            this.updateForm.twoFactorAuth = col.two_factor_auth === 1;
            $('#updateRecordModal').modal('show');
        },
        updateRecord(){
            this.updateForm.processing = true;
            axios.put(route('companies.update-user', { user: this.updateForm.uuid}), this.updateForm).then((response) => {
                if (response.data.status){
                    this.loadPayloadFromApi();
                    this.updateForm.name = undefined;
                    this.updateForm.msisdn = undefined;
                    this.updateForm.email = undefined;
                    this.updateForm.companyPosition = undefined;
                    this.updateForm.twoFactorAuth = undefined;
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
        },
        removeFromCompany(col){
            Swal.fire({
                title: 'Are you sure?',
                text: "You are about to remove this user from your company. This action is irreversible",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, proceed'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.delete(route('companies.remove-user', {company: this.company.uuid, user: col.uuid})).then((response) => {
                        if (response.data.status){
                            this.loadPayloadFromApi();
                            Swal.fire('Success', response.data.message, 'success');
                        } else {
                            Swal.fire('Failed', response.data.message, 'warning');
                        }
                    }).catch((error) => {
                        axiosErrorHandler(error);
                    });
                }
            });

        },
        clearFilter(){
            this.filterForm.name = undefined;
            this.filterForm.msisdn = undefined;
            this.filterForm.email = undefined;
            this.loadPayloadFromApi();
        }
    }
}
</script>

<style scoped>

</style>
