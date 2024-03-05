<template>

    <Head title="Counties" />

    <Layout>
        <div class="row">
            <div class="col-lg-8 col-xl-8 col-md-8 col-sm-8 mx-auto">
                <div class="header">
                    <h1 class="header-title text-center">
                        Create County
                    </h1>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label for="name"><strong>Name</strong></label>
                                    <input v-model="createForm.name" id="name" type="text" class="form-control">
                                </div>
                            </div>
                            <button v-if="createForm.processing" class="btn btn-warning btn-block" type="button" disabled>
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                Processing...
                            </button>
                            <button v-else @click.prevent="createRecord()" type="button" class="btn btn-primary btn-block">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 col-xl-12 col-md-12 col-sm-12">
                <div class="header">
                    <h1>
                        Counties
                    </h1>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-responsive table-bordered" style="width:100%">
                                <thead>
                                <tr>
                                    <th>County Name</th>
                                    <th colspan="2">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="col in payloadFromDb.data" :key="col.uuid">
                                    <td>
                                        <p>{{ col.name }}</p>
                                    </td>
                                    <td>
                                        <button @click.prevent="showUpdateRecordModal(col)" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i> update</button>
                                    </td>
                                    <td>
                                        <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> delete</button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <Bootstrap5Pagination @limit=20 :data="payloadFromDb" @pagination-change-page="loadPayloadFromApi"/>
                    </div>
                </div>
            </div>
        </div>

        <!-- Update Record Modal -->
        <div class="modal fade" id="updateRecordModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Update County</h4>
                    </div>
                    <div class="modal-body">
                        <div class="card">
                            <div class="card-body">
                                <form>
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <label for="name"><strong>Name</strong></label>
                                            <input v-model="updateForm.name" id="name" type="text" class="form-control">
                                        </div>
                                    </div>
                                    <button v-if="updateForm.processing" class="btn btn-warning btn-block" type="button" disabled>
                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                        Processing...
                                    </button>
                                    <button v-else @click.prevent="updateRecord()" type="button" class="btn btn-primary btn-block">Save</button>
                                </form>
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
import Layout from "@/Pages/Layout.vue";

export default {
    name: "County",
    components: {Layout, Link, Head, Bootstrap5Pagination},
    data() {
        return {
            payloadFromDb: {},
            createForm: {
                name: undefined,
                processing: false
            },
            updateForm: {
                countyUuid: undefined,
                name: undefined,
                processing: false
            }
        }
    },
    mounted() {
        this.loadPayloadFromApi();
    },
    methods: {
        loadPayloadFromApi(page = 1){
            const payLoad = {
                sort_by : 'latest'
            };
            this.createForm.processing = true;
            axios.post(route('counties.load', {'page': page}), payLoad).then(response => {
                this.counties = response.data;
            }).catch(error => {
                //
            }).finally(() => {
                this.createForm.processing = false;
            });
        },
        createRecord(){
            this.createForm.processing = true;
            axios.post(route('counties.store'), this.createForm).then((response) => {
                this.createForm.name = undefined;
                Swal.fire('Success!', response.data.message, 'success');
                this.loadPayloadFromApi();
            }).catch((error) => {
                if (error.response && error.response.status === 422) {
                    const errorData = error.response.data;
                    if (errorData.errors) {
                        const errorMessages = Object.values(errorData.errors).flat().map(errorMessage => errorMessage.replace(/\.$/, "")).join(", ");
                        Swal.fire({title: 'Failed!', text: errorMessages, icon: 'error'});
                    } else if (errorData.message) {
                        Swal.fire({title: 'Failed!', text: errorData.message, icon: 'error'});
                    } else {
                        Swal.fire({title: 'Failed!', text: 'An unknown error occurred.', icon: 'error'});
                    }
                } else {
                    Swal.fire({title: 'Failed!', text: error.message, icon: 'error'});
                }
            }).finally(() => {
                this.createForm.processing = false;
            });
        },
        showUpdateRecordModal(record){
            this.updateForm.propertyUuid = record.uuid;
            this.updateForm.name = record.name;
            $('#updateRecordModal').modal('show');
        },
        updateRecord(){
            this.updateForm.processing = true;
            axios.put(route('counties.update', this.updateForm.uuid), this.updateForm).then((response) => {
                if (response.data.status){
                    this.updateForm.name = undefined;
                    Swal.fire('Success!', response.data.message, 'success');
                    $('#updateRecordModal').modal('hide');
                } else {
                    Swal.fire('Error!', response.data.message, 'warning');
                }
                this.loadPayloadFromApi();
            }).catch((error) => {
                if (error.response && error.response.status === 422) {
                    const errorData = error.response.data;
                    if (errorData.errors) {
                        const errorMessages = Object.values(errorData.errors).flat().map(errorMessage => errorMessage.replace(/\.$/, "")).join(", ");
                        Swal.fire({title: 'Failed!', text: errorMessages, icon: 'error'});
                    } else if (errorData.message) {
                        Swal.fire({title: 'Failed!', text: errorData.message, icon: 'error'});
                    } else {
                        Swal.fire({title: 'Failed!', text: 'An unknown error occurred.', icon: 'error'});
                    }
                } else {
                    Swal.fire({title: 'Failed!', text: error.message, icon: 'error'});
                }
            }).finally(() => {
                this.updateForm.processing = false;
            });
        }
    }
}
</script>

<style scoped>

</style>
