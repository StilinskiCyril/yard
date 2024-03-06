<template>

    <Head title="Makes" />

    <Layout>

        <div class="row">
            <div class="col-lg-10 col-xl-10 col-md-10 col-sm-10 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="text-left">Manage Makes</p>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <button @click.prevent="showCreateRecordModal()" class="btn btn-primary btn-sm"><i class="fas fa-circle-plus"></i>
                                    Create Make
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form>
                            <div class="form-group row">
                                <div class="col-md-4">
                                    <input v-model="filterForm.make" type="text" class="form-control" placeholder="Enter make">
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
                                    <th>Logo</th>
                                    <th>Make</th>
                                    <th>No Of Models</th>
                                    <th colspan="3">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="col in payloadFromDb.data" :key="col.uuid">
                                    <td>
                                        <img :src="col.formatted_logo_url" alt="Make logo" height="30" width="30" class="rounded-circle">
                                    </td>
                                    <td>
                                        <p>{{ col.make }}</p>
                                    </td>
                                    <td>
                                        <p>{{ col.make_models_count }}</p>
                                    </td>
                                    <td>
                                        <button @click.prevent="showUpdateRecordModal(col)" class="btn btn-outline-primary btn-sm"><i class="fas fa-edit"></i> update</button>
                                    </td>
                                    <td>
                                        <Link :href="route('makes.show', { make: col.uuid})">
                                            <button class="btn btn-outline-primary btn-sm"><i class="fas fa-car"></i> view models</button>
                                        </Link>
                                    </td>
                                    <td>
                                        <button @click.prevent="deleteRecord(col)" class="btn btn-outline-danger btn-sm"><i class="fas fa-trash"></i> delete</button>
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
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Create Make</h4>
                    </div>
                    <div class="modal-body">
                        <div class="card">
                            <div class="card-body">
                                <form enctype="multipart/form-data">
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <input v-model="createForm.make" placeholder="Enter make e.g BMW" type="text" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row mt-3">
                                        <div class="col-md-12">
                                            <label>Upload Logo</label>
                                            <input v-on:change="onFileChange($event, 'create')" type="file" class="form-control">
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
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Update Make</h4>
                    </div>
                    <div class="modal-body">
                        <div class="card">
                            <div class="card-body">
                                <form enctype="multipart/form-data">
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <input v-model="updateForm.make" placeholder="Enter make" type="text" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row mt-3">
                                        <div class="col-md-12">
                                            <label>Upload Logo</label>
                                            <input v-on:change="onFileChange($event, 'update')" placeholder="Logo" type="file" class="form-control">
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
    name: "Make",
    components: {Layout, Link, Head, Bootstrap5Pagination},
    data() {
        return {
            payloadFromDb: {},
            filterForm: {
                sort_by : 'latest',
                paginate: true,
                make: undefined,
                processing: false
            },
            createForm: {
                make: undefined,
                logo: undefined,
                processing: false
            },
            updateForm: {
                uuid: undefined,
                make: undefined,
                logo: undefined,
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
            axios.post(route('makes.load', {page: page}), this.filterForm).then(response => {
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
        onFileChange(event, type){
            if (type === 'create'){
                this.createForm.logo = event.target.files[0];
            } else {
                this.updateForm.logo = event.target.files[0];
            }
        },
        createRecord(){
            let payLoad = new FormData();
            payLoad.append("make", this.createForm.make || "");
            payLoad.append("logo", this.createForm.logo || "");
            this.createForm.processing = true;
            axios.post(route('makes.store'), payLoad).then((response) => {
                if (response.data.status){
                    this.loadPayloadFromApi();
                    this.createForm.make = undefined;
                    this.createForm.logo = undefined;
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
            this.updateForm.make = col.make;
            $('#updateRecordModal').modal('show');
        },
        updateRecord(){
            let payLoad = new FormData();
            payLoad.append("_method", 'put');
            payLoad.append("make", this.updateForm.make || "");
            payLoad.append("logo", this.updateForm.logo ? this.updateForm.logo : '');

            this.updateForm.processing = true;
            axios.post(route('makes.update', { make : this.updateForm.uuid}), payLoad).then((response) => {
                if (response.data.status){
                    this.loadPayloadFromApi();
                    this.updateForm.make = undefined;
                    this.updateForm.logo = undefined;
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
        deleteRecord(col){
            Swal.fire({
                title: 'Are you sure?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.delete(route('makes.destroy', {make: col.uuid})).then((response) => {
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
            this.filterForm.make = undefined;
            this.loadPayloadFromApi();
        }
    }
}
</script>

<style scoped>

</style>
