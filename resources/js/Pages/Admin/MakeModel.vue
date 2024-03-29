<template>

    <Head :title="`${make.make} Models`" />

    <Layout>

        <div class="row">
            <div class="col-lg-10 col-xl-10 col-md-10 col-sm-10 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="text-left">Manage {{ make.make }} Models</p>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <button @click.prevent="showCreateRecordModal()" class="btn btn-primary btn-sm"><i class="fas fa-circle-plus"></i>
                                    Create Model
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <Link :href="route('makes.index')">Go Back</Link>
                        <form>
                            <div class="form-group row">
                                <div class="col-md-4">
                                    <input v-model="filterForm.model" type="text" class="form-control" placeholder="Enter model">
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
                                    <th>Model</th>
                                    <th colspan="2">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="col in payloadFromDb.data" :key="col.uuid">
                                    <td>
                                        <p>{{ col.model }}</p>
                                    </td>
                                    <td>
                                        <button @click.prevent="showUpdateRecordModal(col)" class="btn btn-outline-primary btn-sm"><i class="fas fa-edit"></i> update</button>
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
                        <h4 class="modal-title">Create Model</h4>
                    </div>
                    <div class="modal-body">
                        <div class="card">
                            <div class="card-body">
                                <form>
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <input v-model="createForm.model" placeholder="Enter model" type="text" class="form-control">
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
                        <h4 class="modal-title">Update Model</h4>
                    </div>
                    <div class="modal-body">
                        <div class="card">
                            <div class="card-body">
                                <form>
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <input v-model="updateForm.model" placeholder="Enter model" type="text" class="form-control">
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
    name: "County",
    props: ['make'],
    components: {Layout, Link, Head, Bootstrap5Pagination},
    data() {
        return {
            payloadFromDb: {},
            filterForm: {
                sortBy : 'latest',
                paginate: true,
                model: undefined,
                processing: false
            },
            createForm: {
                uuid: undefined,
                model: undefined,
                processing: false
            },
            updateForm: {
                uuid: undefined,
                model: undefined,
                processing: false
            }
        }
    },
    mounted() {
        this.loadPayloadFromApi();
        this.createForm.uuid = this.make.uuid;
    },
    methods: {
        loadPayloadFromApi(page = 1){
            this.filterForm.processing = true;
            axios.post(route('make-models.load', {page: page, make: this.make.uuid}), this.filterForm).then(response => {
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
            axios.post(route('make-models.store'), this.createForm).then((response) => {
                if (response.data.status){
                    this.loadPayloadFromApi();
                    this.createForm.model = undefined;
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
            this.updateForm.model = col.model;
            $('#updateRecordModal').modal('show');
        },
        updateRecord(){
            this.updateForm.processing = true;
            axios.put(route('make-models.update', { make_model: this.updateForm.uuid}), this.updateForm).then((response) => {
                if (response.data.status){
                    this.loadPayloadFromApi();
                    this.updateForm.model = undefined;
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
                    axios.delete(route('make-models.destroy', {make_model: col.uuid})).then((response) => {
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
            this.filterForm.model = undefined;
            this.loadPayloadFromApi();
        }
    }
}
</script>

<style scoped>

</style>
