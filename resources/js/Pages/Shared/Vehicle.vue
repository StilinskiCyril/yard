<template>

    <Head title="Vehicles" />

    <Layout>

        <div class="row">
            <div class="col-lg-12 col-xl-12 col-md-12 col-sm-12 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="text-left">Manage Vehicles</p>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <button @click.prevent="showCreateRecordModal()" class="btn btn-primary btn-sm"><i class="fas fa-circle-plus"></i>
                                    Create Vehicle
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form>
                            <div class="form-group row">
                                <div class="col-md-2">
                                    <label>Vehicle Name</label>
                                    <input v-model="filterForm.name" type="text" class="form-control">
                                </div>
                                <div class="col-md-2">
                                    <label>Select Make</label>
                                    <v-select @change="loadMakeModels('filter')" class="form-control" :options="makes" :reduce="make => make.uuid" label="make" v-model="filterForm.makeUuid"></v-select>
                                </div>
                                <div class="col-md-2">
                                    <label>Select Model</label>
                                    <v-select class="form-control" :options="makeModels" :reduce="model => model.id" label="model" v-model="filterForm.makeModelId"></v-select>
                                </div>
                                <div class="col-md-2">
                                    <label>Transmission Type</label>
                                    <v-select class="form-control" :options="transmissionTypes" :reduce="type => type.id" label="type" v-model="filterForm.transmissionTypeId"></v-select>
                                </div>
                                <div class="col-md-2">
                                    <label>Vehicle Condition</label>
                                    <v-select class="form-control" :options="vehicleConditions" :reduce="condition => condition.id" label="condition" v-model="filterForm.vehicleConditionId"></v-select>
                                </div>
                                <div class="col-md-2">
                                    <label>Fuel Type</label>
                                    <v-select class="form-control" :options="fuelTypes" :reduce="type => type.id" label="type" v-model="filterForm.fuelTypeId"></v-select>
                                </div>
                            </div>
                            <div class="form-group row mt-3">
                                <div class="col-md-2">
                                    <label>Drive Type</label>
                                    <v-select class="form-control" :options="driveTypes" :reduce="type => type.id" label="type" v-model="filterForm.driveTypeId"></v-select>
                                </div>
                                <div class="col-md-2">
                                    <label>Body Type</label>
                                    <v-select class="form-control" :options="bodyTypes" :reduce="type => type.id" label="type" v-model="filterForm.bodyTypeId"></v-select>
                                </div>
                                <div class="col-md-2">
                                    <label>Drive Setup</label>
                                    <v-select class="form-control" :options="driveSetups" :reduce="setup => setup.id" label="setup" v-model="filterForm.driveSetupId"></v-select>
                                </div>
                                <div class="col-md-2">
                                    <label>Is Featured</label>
                                    <select v-model="filterForm.isFeatured" class="form-control">
                                        <option :value="0">YES</option>
                                        <option :value="1">NO</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label>Availability</label>
                                    <select v-model="filterForm.availability" class="form-control">
                                        <option :value="1">AVAILABLE</option>
                                        <option :value="2">SOLD</option>
                                        <option :value="3">IN-TRANSIT</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label>Payment Status</label>
                                    <select v-model="filterForm.paymentStatus" class="form-control">
                                        <option :value="0">PENDING</option>
                                        <option :value="1">PAID</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row mt-3">
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
                                    <th>Cover Photo</th>
                                    <th>Video </th>
                                    <th>Name</th>
                                    <th>Make</th>
                                    <th>Model</th>
                                    <th>Transmission Type</th>
                                    <th>Vehicle Condition</th>
                                    <th>Engine Capacity (CC)</th>
                                    <th>Fuel Type</th>
                                    <th>Drive Type</th>
                                    <th>Body Type</th>
                                    <th>Drive Setup</th>
                                    <th>Currency</th>
                                    <th>Price</th>
                                    <th>Millage (KM)</th>
                                    <th>Y.O.M</th>
                                    <th>Color</th>
                                    <th>Horse Power (HP)</th>
                                    <th>Torque (NM)</th>
                                    <th>Is Featured</th>
                                    <th>Availability</th>
                                    <th>Status</th>
                                    <th>Features</th>
                                    <th colspan="3">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="col in payloadFromDb.data" :key="col.uuid">
                                    <td>
                                        <img :src="col.formatted_cover_photo_url" alt="Vehicle cover photo" height="30" width="30" class="rounded-circle">
                                    </td>
                                    <td>
                                        <a :href="col.youtube_url">open</a>
                                    </td>
                                    <td>
                                        <p>{{ col.name }}</p>
                                    </td>
                                    <td>
                                        <p>{{ col.make_model.make.make }}</p>
                                    </td>
                                    <td>
                                        <p>{{ col.make_model.model }}</p>
                                    </td>
                                    <td>
                                        <p>{{ col.transmission_type.type }}</p>
                                    </td>
                                    <td>
                                        <p>{{ col.vehicle_condition.condition }}</p>
                                    </td>
                                    <td>
                                        <p>{{ col.engine_capacity }}</p>
                                    </td>
                                    <td>
                                        <p>{{ col.fuel_type.type }}</p>
                                    </td>
                                    <td>
                                        <p>{{ col.drive_type.type }}</p>
                                    </td>
                                    <td>
                                        <p>{{ col.body_type.type }}</p>
                                    </td>
                                    <td>
                                        <p>{{ col.drive_setup.setup }}</p>
                                    </td>
                                    <td>
                                        <p>{{ col.currency.currency }}</p>
                                    </td>
                                    <td>
                                        <p>{{ col.price.toLocaleString() }}</p>
                                    </td>
                                    <td>
                                        <p>{{ col.mileage.toLocaleString() }}</p>
                                    </td>
                                    <td>
                                        <p>{{ col.yom }}</p>
                                    </td>
                                    <td>
                                        <p>{{ col.color }}</p>
                                    </td>
                                    <td>
                                        <p>{{ col.horse_power.toLocaleString() }}</p>
                                    </td>
                                    <td>
                                        <p>{{ col.torque.toLocaleString() }}</p>
                                    </td>
                                    <td>
                                        <p v-if="col.is_featured === 1">YES</p>
                                        <p v-else>NO</p>
                                    </td>
                                    <td>
                                        <p v-if="col.availability === 1">AVAILABLE</p>
                                        <p v-else-if="col.availability === 2">SOLD</p>
                                        <p v-else>IN-TRANSIT</p>
                                    </td>
                                    <td>
                                        <p v-if="col.status === 1">APPROVED</p>
                                        <p v-else>PENDING</p>
                                    </td>
                                    <td>
                                        <button @click.prevent="showMoreModal(col)" class="btn btn-outline-primary btn-sm"><i class="fas fa-question"></i> view features</button>
                                    </td>
                                    <td>
                                        <button @click.prevent="showUpdateRecordModal(col)" class="btn btn-outline-primary btn-sm"><i class="fas fa-edit"></i> update</button>
                                    </td>
                                    <td>
                                        <button @click.prevent="showAddImagesModal(col)" class="btn btn-outline-primary btn-sm"><i class="fas fa-image"></i> add images</button>
                                    </td>
                                    <td>
                                        <button v-if="col.payment_status === 0" @click.prevent="showUpdateRecordModal(col)" class="btn btn-outline-primary btn-sm"><i class="fas fa-credit-card"></i> pay</button>
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
                        <h4 class="modal-title">Create Vehicle</h4>
                    </div>
                    <div class="modal-body">
                        <div class="card">
                            <div class="card-body">
                                <form enctype="multipart/form-data">
                                    <div class="form-group row">
                                        <label class="col-md-2">Vehicle Name</label>
                                        <div class="col-md-10">
                                            <input v-model="createForm.name" type="text" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row mt-3">
                                        <label class="col-md-2">Select Make</label>
                                        <div class="col-md-10">
                                            <v-select @change="loadMakeModels('create')" class="form-control" :options="makes" :reduce="make => make.uuid" label="make" v-model="createForm.makeUuid"></v-select>
                                        </div>
                                    </div>
                                    <div class="form-group row mt-3">
                                        <label class="col-md-2">Select Model</label>
                                        <div class="col-md-10">
                                            <v-select class="form-control" :options="makeModels" :reduce="model => model.id" label="model" v-model="createForm.makeModelId"></v-select>
                                        </div>
                                    </div>
                                    <div class="form-group row mt-3">
                                        <label class="col-md-2">Transmission Type</label>
                                        <div class="col-md-10">
                                            <v-select class="form-control" :options="transmissionTypes" :reduce="type => type.id" label="type" v-model="createForm.transmissionTypeId"></v-select>
                                        </div>
                                    </div>
                                    <div class="form-group row mt-3">
                                        <label class="col-md-2">Vehicle Condition</label>
                                        <div class="col-md-10">
                                            <v-select class="form-control" :options="vehicleConditions" :reduce="condition => condition.id" label="condition" v-model="createForm.vehicleConditionId"></v-select>
                                        </div>
                                    </div>
                                    <div class="form-group row mt-3">
                                        <label class="col-md-2">Engine Capacity (CC)</label>
                                        <div class="col-md-10">
                                            <input v-model="createForm.engineCapacity" type="number" min="1" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row mt-3">
                                        <label class="col-md-2">Fuel Type</label>
                                        <div class="col-md-10">
                                            <v-select class="form-control" :options="fuelTypes" :reduce="type => type.id" label="type" v-model="createForm.fuelTypeId"></v-select>
                                        </div>
                                    </div>
                                    <div class="form-group row mt-3">
                                        <label class="col-md-2">Drive Type</label>
                                        <div class="col-md-10">
                                            <v-select class="form-control" :options="driveTypes" :reduce="type => type.id" label="type" v-model="createForm.driveTypeId"></v-select>
                                        </div>
                                    </div>
                                    <div class="form-group row mt-3">
                                        <label class="col-md-2">Body Type</label>
                                        <div class="col-md-10">
                                            <v-select class="form-control" :options="bodyTypes" :reduce="type => type.id" label="type" v-model="createForm.bodyTypeId"></v-select>
                                        </div>
                                    </div>
                                    <div class="form-group row mt-3">
                                        <label class="col-md-2">Drive Setup</label>
                                        <div class="col-md-10">
                                            <v-select class="form-control" :options="driveSetups" :reduce="setup => setup.id" label="setup" v-model="createForm.driveSetupId"></v-select>
                                        </div>
                                    </div>
                                    <div class="form-group row mt-3">
                                        <label class="col-md-2">Currency</label>
                                        <div class="col-md-10">
                                            <v-select class="form-control" :options="currencies" :reduce="currency => currency.id" label="currency" v-model="createForm.currencyId"></v-select>
                                        </div>
                                    </div>
                                    <div class="form-group row mt-3">
                                        <label class="col-md-2">Price</label>
                                        <div class="col-md-10">
                                            <input v-model="createForm.price" type="number" min="1" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row mt-3">
                                        <label class="col-md-2">Millage (KM)</label>
                                        <div class="col-md-10">
                                            <input v-model="createForm.mileage" type="number" min="1" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row mt-3">
                                        <label class="col-md-2">Y.O.M</label>
                                        <div class="col-md-10">
                                            <input v-model="createForm.yom" type="text" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row mt-3">
                                        <label class="col-md-2">Color</label>
                                        <div class="col-md-10">
                                            <input v-model="createForm.color" type="text" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row mt-3">
                                        <label class="col-md-2">Horse Power (HP)</label>
                                        <div class="col-md-10">
                                            <input v-model="createForm.horsePower" type="number" min="1" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row mt-3">
                                        <label class="col-md-2">Torque (NM)</label>
                                        <div class="col-md-10">
                                            <input v-model="createForm.torque" type="number" min="1" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row mt-3">
                                        <label class="col-md-2">Cover Photo</label>
                                        <div class="col-md-10">
                                            <input v-on:change="onFileChange($event, 'create')" type="file" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row mt-3">
                                        <label class="col-md-2">Youtube Video Link (optional)</label>
                                        <div class="col-md-10">
                                            <input v-model="createForm.youtubeUrl" type="url" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row mt-3">
                                        <label class="col-md-2">Availability</label>
                                        <div class="col-md-10">
                                            <select v-model="createForm.availability" class="form-control">
                                                <option :value="1">Available</option>
                                                <option :value="3">In-Transit</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row mt-3">
                                        <label class="col-md-2">Features</label>
                                        <div class="col-md-10">
                                            <textarea v-model="createForm.features" class="form-control" rows="7"></textarea>
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
                        <h4 class="modal-title">Update Vehicle</h4>
                    </div>
                    <div class="modal-body">
                        <div class="card">
                            <div class="card-body">
                                <form enctype="multipart/form-data">
                                    <div class="form-group row">
                                        <label class="col-md-2">Vehicle Name</label>
                                        <div class="col-md-10">
                                            <input v-model="updateForm.name" type="text" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row mt-3">
                                        <label class="col-md-2">Select Make</label>
                                        <div class="col-md-10">
                                            <v-select @change="loadMakeModels('update')" class="form-control" :options="makes" :reduce="make => make.uuid" label="make" v-model="updateForm.makeUuid"></v-select>
                                        </div>
                                    </div>
                                    <div class="form-group row mt-3">
                                        <label class="col-md-2">Select Model</label>
                                        <div class="col-md-10">
                                            <v-select class="form-control" :options="makeModels" :reduce="model => model.id" label="model" v-model="updateForm.makeModelId"></v-select>
                                        </div>
                                    </div>
                                    <div class="form-group row mt-3">
                                        <label class="col-md-2">Transmission Type</label>
                                        <div class="col-md-10">
                                            <v-select class="form-control" :options="transmissionTypes" :reduce="type => type.id" label="type" v-model="updateForm.transmissionTypeId"></v-select>
                                        </div>
                                    </div>
                                    <div class="form-group row mt-3">
                                        <label class="col-md-2">Vehicle Condition</label>
                                        <div class="col-md-10">
                                            <v-select class="form-control" :options="vehicleConditions" :reduce="condition => condition.id" label="condition" v-model="updateForm.vehicleConditionId"></v-select>
                                        </div>
                                    </div>
                                    <div class="form-group row mt-3">
                                        <label class="col-md-2">Engine Capacity (CC)</label>
                                        <div class="col-md-10">
                                            <input v-model="updateForm.engineCapacity" type="number" min="1" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row mt-3">
                                        <label class="col-md-2">Fuel Type</label>
                                        <div class="col-md-10">
                                            <v-select class="form-control" :options="fuelTypes" :reduce="type => type.id" label="type" v-model="updateForm.fuelTypeId"></v-select>
                                        </div>
                                    </div>
                                    <div class="form-group row mt-3">
                                        <label class="col-md-2">Drive Type</label>
                                        <div class="col-md-10">
                                            <v-select class="form-control" :options="driveTypes" :reduce="type => type.id" label="type" v-model="updateForm.driveTypeId"></v-select>
                                        </div>
                                    </div>
                                    <div class="form-group row mt-3">
                                        <label class="col-md-2">Body Type</label>
                                        <div class="col-md-10">
                                            <v-select class="form-control" :options="bodyTypes" :reduce="type => type.id" label="type" v-model="updateForm.bodyTypeId"></v-select>
                                        </div>
                                    </div>
                                    <div class="form-group row mt-3">
                                        <label class="col-md-2">Drive Setup</label>
                                        <div class="col-md-10">
                                            <v-select class="form-control" :options="driveSetups" :reduce="setup => setup.id" label="setup" v-model="updateForm.driveSetupId"></v-select>
                                        </div>
                                    </div>
                                    <div class="form-group row mt-3">
                                        <label class="col-md-2">Currency</label>
                                        <div class="col-md-10">
                                            <v-select class="form-control" :options="currencies" :reduce="currency => currency.id" label="currency" v-model="updateForm.currencyId"></v-select>
                                        </div>
                                    </div>
                                    <div class="form-group row mt-3">
                                        <label class="col-md-2">Price</label>
                                        <div class="col-md-10">
                                            <input v-model="updateForm.price" type="number" min="1" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row mt-3">
                                        <label class="col-md-2">Millage (KM)</label>
                                        <div class="col-md-10">
                                            <input v-model="updateForm.mileage" type="number" min="1" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row mt-3">
                                        <label class="col-md-2">Y.O.M</label>
                                        <div class="col-md-10">
                                            <input v-model="updateForm.yom" type="text" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row mt-3">
                                        <label class="col-md-2">Color</label>
                                        <div class="col-md-10">
                                            <input v-model="updateForm.color" type="text" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row mt-3">
                                        <label class="col-md-2">Horse Power (HP)</label>
                                        <div class="col-md-10">
                                            <input v-model="updateForm.horsePower" type="number" min="1" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row mt-3">
                                        <label class="col-md-2">Torque (NM)</label>
                                        <div class="col-md-10">
                                            <input v-model="updateForm.torque" type="number" min="1" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row mt-3">
                                        <label class="col-md-2">Cover Photo</label>
                                        <div class="col-md-10">
                                            <input v-on:change="onFileChange($event, 'update')" type="file" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row mt-3">
                                        <label class="col-md-2">Youtube Video Link (optional)</label>
                                        <div class="col-md-10">
                                            <input v-model="updateForm.youtubeUrl" type="url" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row mt-3">
                                        <label class="col-md-2">Availability</label>
                                        <div class="col-md-10">
                                            <select v-model="updateForm.availability" class="form-control">
                                                <option :value="1">Available</option>
                                                <option :value="3">In-Transit</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row mt-3">
                                        <label class="col-md-2">Features</label>
                                        <div class="col-md-10">
                                            <textarea v-model="updateForm.features" class="form-control" rows="7"></textarea>
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

        <!-- Show More Modal -->
        <div class="modal fade" id="showMoreModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ showMore.vehicleName }} Features</h4>
                    </div>
                    <div class="modal-body">
                        <div class="card">
                            <div class="card-body">
                                <div v-html="showMore.text"></div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
    name: "Vehicle",
    components: {Layout, Link, Head, Bootstrap5Pagination},
    data() {
        return {
            payloadFromDb: {},
            makes: [],
            makeModels: [],
            transmissionTypes: [],
            vehicleConditions: [],
            fuelTypes: [],
            driveTypes: [],
            bodyTypes: [],
            driveSetups: [],
            currencies: [],
            showMore: {
                vehicleName: undefined,
                text: undefined
            },
            filterForm: {
                sortBy : 'latest',
                paginate: true,
                name: undefined,
                makeUuid: undefined,
                makeModelId: undefined,
                transmissionTypeId: undefined,
                vehicleConditionId: undefined,
                engineCapacity: undefined,
                fuelTypeId: undefined,
                driveTypeId: undefined,
                bodyTypeId: undefined,
                driveSetupId: undefined,
                currencyId: undefined,
                price: undefined,
                mileage: undefined,
                yom: undefined,
                color: undefined,
                horsePower: undefined,
                torque: undefined,
                features: undefined,
                isFeatured: undefined,
                availability: undefined,
                paymentStatus: undefined,
                processing: false
            },
            createForm: {
                name: undefined,
                makeUuid: undefined,
                makeModelId: undefined,
                transmissionTypeId: undefined,
                vehicleConditionId: undefined,
                engineCapacity: undefined,
                fuelTypeId: undefined,
                driveTypeId: undefined,
                bodyTypeId: undefined,
                driveSetupId: undefined,
                currencyId: undefined,
                price: undefined,
                mileage: undefined,
                coverPhoto: undefined,
                youtubeUrl: undefined,
                yom: undefined,
                color: undefined,
                horsePower: undefined,
                torque: undefined,
                features: undefined,
                availability: undefined,
                processing: false
            },
            updateForm: {
                uuid: undefined,
                name: undefined,
                makeUuid: undefined,
                makeModelId: undefined,
                transmissionTypeId: undefined,
                vehicleConditionId: undefined,
                engineCapacity: undefined,
                fuelTypeId: undefined,
                driveTypeId: undefined,
                bodyTypeId: undefined,
                driveSetupId: undefined,
                currencyId: undefined,
                price: undefined,
                mileage: undefined,
                coverPhoto: undefined,
                youtubeUrl: undefined,
                yom: undefined,
                color: undefined,
                horsePower: undefined,
                torque: undefined,
                features: undefined,
                availability: undefined,
                processing: false
            }
        }
    },
    mounted() {
        this.loadPayloadFromApi();
        this.loadMakes();
        this.loadTransmissionTypes();
        this.loadVehicleConditions();
        this.loadFuelTypes();
        this.loadDriveTypes();
        this.loadBodyTypes();
        this.loadDriveSetups();
        this.loadCurrencies();
    },
    methods: {
        loadPayloadFromApi(page = 1){
            this.filterForm.processing = true;
            axios.post(route('vehicles.load', {page: page}), this.filterForm).then(response => {
                this.payloadFromDb = response.data;
            }).catch(error => {
                //
            }).finally(() => {
                this.filterForm.processing = false;
            });
        },
        loadMakes(){
            this.filterForm.processing = true;
            axios.post(route('makes.load'), {sortBy: 'latest', paginate: false}).then(response => {
                this.makes = response.data;
            }).catch(error => {
                //
            }).finally(() => {
                this.filterForm.processing = false;
            });
        },
        loadMakeModels(parentForm){
            let uuid;
            if (parentForm === 'filter'){
                uuid = this.filterForm.makeUuid;
            } else if(parentForm === 'create') {
                uuid = this.createForm.makeUuid;
            } else {
                uuid = this.updateForm.makeUuid;
            }

            this.filterForm.processing = true;
            axios.post(route('make-models.load', {make: uuid}), {sortBy: 'latest', paginate: false}).then(response => {
                this.makeModels = response.data;
            }).catch(error => {
                //
            }).finally(() => {
                this.filterForm.processing = false;
            });
        },
        loadTransmissionTypes(){
            this.filterForm.processing = true;
            axios.post(route('transmission-types.load'), {sortBy: 'latest', paginate: false}).then(response => {
                this.transmissionTypes = response.data;
            }).catch(error => {
                //
            }).finally(() => {
                this.filterForm.processing = false;
            });
        },
        loadVehicleConditions(){
            this.filterForm.processing = true;
            axios.post(route('vehicle-conditions.load'), {sortBy: 'latest', paginate: false}).then(response => {
                this.vehicleConditions = response.data;
            }).catch(error => {
                //
            }).finally(() => {
                this.filterForm.processing = false;
            });
        },
        loadFuelTypes(){
            this.filterForm.processing = true;
            axios.post(route('fuel-types.load'), {sortBy: 'latest', paginate: false}).then(response => {
                this.fuelTypes = response.data;
            }).catch(error => {
                //
            }).finally(() => {
                this.filterForm.processing = false;
            });
        },
        loadDriveTypes(){
            this.filterForm.processing = true;
            axios.post(route('drive-types.load'), {sortBy: 'latest', paginate: false}).then(response => {
                this.driveTypes = response.data;
            }).catch(error => {
                //
            }).finally(() => {
                this.filterForm.processing = false;
            });
        },
        loadBodyTypes(){
            this.filterForm.processing = true;
            axios.post(route('body-types.load'), {sortBy: 'latest', paginate: false}).then(response => {
                this.bodyTypes = response.data;
            }).catch(error => {
                //
            }).finally(() => {
                this.filterForm.processing = false;
            });
        },
        loadDriveSetups(){
            this.filterForm.processing = true;
            axios.post(route('drive-setups.load'), {sortBy: 'latest', paginate: false}).then(response => {
                this.driveSetups = response.data;
            }).catch(error => {
                //
            }).finally(() => {
                this.filterForm.processing = false;
            });
        },
        loadCurrencies(){
            this.filterForm.processing = true;
            axios.post(route('currencies.load'), {sortBy: 'latest', paginate: false}).then(response => {
                this.currencies = response.data;
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
                this.createForm.coverPhoto = event.target.files[0];
            } else {
                this.updateForm.coverPhoto = event.target.files[0];
            }
        },
        createRecord(){
            let payLoad = new FormData();
            payLoad.append("name", this.createForm.name || "");
            payLoad.append("makeModelId", this.createForm.makeModelId || "");
            payLoad.append("transmissionTypeId", this.createForm.transmissionTypeId || "");
            payLoad.append("vehicleConditionId", this.createForm.vehicleConditionId || "");
            payLoad.append("engineCapacity", this.createForm.engineCapacity || "");
            payLoad.append("fuelTypeId", this.createForm.fuelTypeId || "");
            payLoad.append("driveTypeId", this.createForm.driveTypeId || "");
            payLoad.append("bodyTypeId", this.createForm.bodyTypeId || "");
            payLoad.append("driveSetupId", this.createForm.driveSetupId || "");
            payLoad.append("currencyId", this.createForm.currencyId || "");
            payLoad.append("price", this.createForm.price || "");
            payLoad.append("mileage", this.createForm.mileage || "");
            payLoad.append("coverPhoto", this.createForm.coverPhoto || "");
            payLoad.append("youtubeUrl", this.createForm.youtubeUrl || "");
            payLoad.append("yom", this.createForm.yom || "");
            payLoad.append("color", this.createForm.color || "");
            payLoad.append("horsePower", this.createForm.horsePower || "");
            payLoad.append("torque", this.createForm.torque || "");
            payLoad.append("availability", this.createForm.availability || "");
            payLoad.append("features", this.createForm.features || "");

            this.createForm.processing = true;
            axios.post(route('vehicles.store'), payLoad).then((response) => {
                if (response.data.status){
                    this.clearFilter();
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
            this.updateForm.makeUuid = col.make_model.make.uuid;
            this.loadMakeModels('update');
            this.updateForm.makeModelId = col.make_model_id;
            this.updateForm.transmissionTypeId = col.transmission_type.id;
            this.updateForm.vehicleConditionId = col.vehicle_condition.id;
            this.updateForm.engineCapacity = col.engine_capacity;
            this.updateForm.fuelTypeId = col.fuel_type.id;
            this.updateForm.driveTypeId = col.drive_type.id;
            this.updateForm.bodyTypeId = col.body_type.id;
            this.updateForm.driveSetupId = col.drive_setup.id;
            this.updateForm.currencyId = col.currency.id;
            this.updateForm.price = col.price;
            this.updateForm.mileage = col.mileage;
            this.updateForm.youtubeUrl = col.youtube_url;
            this.updateForm.yom = col.yom;
            this.updateForm.color = col.color;
            this.updateForm.horsePower = col.horse_power;
            this.updateForm.torque = col.torque;
            this.updateForm.availability = col.availability;
            this.updateForm.features = col.features;
            $('#updateRecordModal').modal('show');
        },
        updateRecord(){
            let payLoad = new FormData();
            payLoad.append("_method", 'put');
            payLoad.append("name", this.updateForm.name || "");
            payLoad.append("makeModelId", this.updateForm.makeModelId || "");
            payLoad.append("transmissionTypeId", this.updateForm.transmissionTypeId || "");
            payLoad.append("vehicleConditionId", this.updateForm.vehicleConditionId || "");
            payLoad.append("engineCapacity", this.updateForm.engineCapacity || "");
            payLoad.append("fuelTypeId", this.updateForm.fuelTypeId || "");
            payLoad.append("driveTypeId", this.updateForm.driveTypeId || "");
            payLoad.append("bodyTypeId", this.updateForm.bodyTypeId || "");
            payLoad.append("driveSetupId", this.updateForm.driveSetupId || "");
            payLoad.append("currencyId", this.updateForm.currencyId || "");
            payLoad.append("price", this.updateForm.price || "");
            payLoad.append("mileage", this.updateForm.mileage || "");
            payLoad.append("coverPhoto", this.updateForm.coverPhoto ? this.updateForm.coverPhoto : '');
            payLoad.append("youtubeUrl", this.updateForm.youtubeUrl || "");
            payLoad.append("yom", this.updateForm.yom || "");
            payLoad.append("color", this.updateForm.color || "");
            payLoad.append("horsePower", this.updateForm.horsePower || "");
            payLoad.append("torque", this.updateForm.torque || "");
            payLoad.append("availability", this.updateForm.availability || "");
            payLoad.append("features", this.updateForm.features || "");

            this.updateForm.processing = true;
            axios.post(route('vehicles.update', { vehicle: this.updateForm.uuid}), payLoad).then((response) => {
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
        },
        showAddImagesModal(col){
            Swal.fire('Info', 'Coming soon...', 'info');
        },
        showMoreModal(col){
            this.showMore.vehicleName = col.name;
            this.showMore.text = col.features;
            $('#showMoreModal').modal('show');
        },
        clearFilter(){
            this.filterForm.name = undefined;
            this.filterForm.makeUuid = undefined;
            this.filterForm.makeModelId = undefined;
            this.filterForm.transmissionTypeId = undefined;
            this.filterForm.vehicleConditionId = undefined;
            this.filterForm.engineCapacity = undefined;
            this.filterForm.fuelTypeId = undefined;
            this.filterForm.driveTypeId = undefined;
            this.filterForm.bodyTypeId = undefined;
            this.filterForm.driveSetupId = undefined;
            this.filterForm.currencyId = undefined;
            this.filterForm.price = undefined;
            this.filterForm.mileage = undefined;
            this.filterForm.yom = undefined;
            this.filterForm.color = undefined;
            this.filterForm.horsePower = undefined;
            this.filterForm.torque = undefined;
            this.filterForm.features = undefined;
            this.loadPayloadFromApi();
        }
    }
}
</script>

<style scoped>

</style>
