<template>
    <div id="employees-table">
        <loader :loading="loading"></loader>
        <div class="row">
            <div class="col-md-6">
                <filter-bar></filter-bar>
            </div>
            <div class="col-md-6">
                <el-button class="pull-right" type="primary" style="margin-top: 8px;" @click="openPostFormDialog">Add Employee</el-button>
            </div>
        </div>
        <vuetable ref="vuetable"
            :api-url="apiUrl"
            :fields="fields"
            pagination-path=""
            :css="css.table"
            :sort-order="sortOrder"
            :multi-sort="true"
            :append-params="moreParams"
            @vuetable:loading="loading = true"
            @vuetable:loaded="loading = false"
            @vuetable:pagination-data="onPaginationData">
        </vuetable>
        <div class="vuetable-pagination">
            <vuetable-pagination-info ref="paginationInfo"
                info-class="pagination-info">
            </vuetable-pagination-info>
            <vuetable-pagination ref="pagination"
                :css="css.pagination"
                @vuetable-pagination:change-page="onChangePage">
            </vuetable-pagination>
        </div>
        <el-dialog
            title="New Employee"
            :visible.sync="postFormDialog"
            width="40%"
            @close="resetFields">
            <el-form label-position="top" :model="employee">
                <el-form-item label="Name">
                    <el-input v-model="employee.name"></el-input>
                    <div v-if="errors.name && errors.name.length">
                        <span class="help-block" v-for="(error, index) in errors.name" :key="index">
                            {{ error }}
                        </span>
                    </div>
                </el-form-item>
                <el-form-item label="Email">
                    <el-input v-model="employee.email"></el-input>
                    <div v-if="errors.email && errors.email.length">
                        <span class="help-block" v-for="(error, index) in errors.email" :key="index">
                            {{ error }}
                        </span>
                    </div>
                </el-form-item>
                <el-form-item label="Birthday">
                    <el-date-picker
                        type="date"
                        placeholder=""
                        v-model="employee.birthdate">
                    </el-date-picker>
                    <div v-if="errors.birthdate && errors.birthdate.length">
                        <span class="help-block" v-for="(error, index) in errors.birthdate" :key="index">
                            {{ error }}
                        </span>
                    </div>
                </el-form-item>
                <el-form-item label="Blood Type">
                    <el-select placeholder="Select" v-model="employee.blood_type">
                        <el-option
                            v-for="item in bloodTypes"
                            :key="item.value"
                            :label="item.label"
                            :value="item.value">
                        </el-option>
                    </el-select>
                    <div v-if="errors.blod_type && errors.blod_type.length">
                        <span class="help-block" v-for="(error, index) in errors.blod_type" :key="index">
                            {{ error }}
                        </span>
                    </div>
                </el-form-item>
                <el-form-item label="Image">
                    <el-button class="btn-file" type="info" size="small" v-if="!employee.image">
                        Browse <input type="file" @change="onFileChange">
                    </el-button>
                    <img :src="employee.image" class="image-preview" alt="Avatar" v-if="employee.image">
                    <el-button type="danger" size="small" @click="removeImage" v-if="employee.image">
                        Remove
                    </el-button>
                    <div v-if="errors.image && errors.image.length">
                        <span class="help-block" v-for="(error, index) in errors.image" :key="index">
                            {{ error }}
                        </span>
                    </div>
                </el-form-item>
                <el-form-item label="Signature" id="signature">
                    <div class="signature-field-container">
                        <VueSignaturePad
                            width="300px"
                            height="100px"
                            :options="{ minWidth: 1, maxWidth: 1.5 }"
                            ref="signaturePad"
                        />
                    </div>
                    <div v-if="errors.signature && errors.signature.length">
                        <span class="help-block" v-for="(error, index) in errors.signature" :key="index">
                            {{ error }}
                        </span>
                    </div>
                </el-form-item>
            </el-form>
            <span slot="footer" class="dialog-footer">
                <el-button @click="closePostFormDialog">Cancel</el-button>
                <el-button type="primary" @click="submit">Submit</el-button>
            </span>
        </el-dialog>
    </div>
</template>

<script>
    import Vue from 'vue'
    import moment from 'moment'
    import Vuetable from 'vuetable-2/src/components/Vuetable'
    import VuetablePagination from 'vuetable-2/src/components/VuetablePagination'
    import VuetablePaginationInfo from 'vuetable-2/src/components/VuetablePaginationInfo'
    import VueEvents from 'vue-events'
    import VueSignaturePad from 'vue-signature-pad'
    import CustomActions from './EmployeesTableCustomActions'
    import FilterBar from './EmployeesTableFilterBar'

    Vue.use(VueEvents);
    Vue.use(VueSignaturePad)

    Vue.component('custom-actions', CustomActions)
    Vue.component('filter-bar', FilterBar)

    export default {
        components: {
            Vuetable,
            VuetablePagination,
            VuetablePaginationInfo,
        },
        data () {
            return {
                fields: [
                    {
                        name: 'name',
                        sortField: 'name',
                    },
                    {
                        name: 'email',
                        sortField: 'email'
                    },
                    {
                        name: 'birthdate',
                        sortField: 'birthdate',
                        callback: '__formatDate|MMMM D, YYYY'
                    },
                    {
                        name: 'age',
                        sortField: 'age',
                    },
                    {
                        name: 'blood_type',
                        title: 'Blood Type',
                        sortField: 'blood_type',
                        callback: '__allcap'
                    },
                    {
                        name: '__component:custom-actions',
                        title: 'Actions',
                        titleClass: 'text-center',
                        dataClass: 'text-center'
                    }
                ],
                css: {
                    table: {
                        tableClass: 'table table-bordered table-striped table-hover',
                        ascendingIcon: 'fa fa-chevron-up',
                        descendingIcon: 'fa fa-chevron-down'
                    },
                    pagination: {
                        infoClass: 'pull-left',
                        wrapperClass: 'vuetable-pagination pull-right',
                        activeClass: 'btn-primary',
                        disabledClass: 'disabled',
                        pageClass: 'btn btn-border',
                        linkClass: 'btn btn-border',
                        icons: {
                            first: '',
                            prev: '',
                            next: '',
                            last: '',
                        },
                    },
                    icons: {
                        first: 'glyphicon glyphicon-step-backward',
                        prev: 'glyphicon glyphicon-chevron-left',
                        next: 'glyphicon glyphicon-chevron-right',
                        last: 'glyphicon glyphicon-step-forward',
                    },
                },
                sortOrder: [
                    {
                        field: 'email',
                        sortField: 'email',
                        direction: 'asc'
                    }
                ],
                moreParams: {},
                employee: {
                    id: null,
                    name: null,
                    email: null,
                    birthdate: null,
                    blood_type: null,
                    image: null,
                    signature: null
                },
                image: null,
                errors: [],
                bloodTypes: [
                    {
                        label: 'A',
                        value: 'a'
                    },
                    {
                        label: 'B',
                        value: 'b'
                    },
                    {
                        label: 'AB',
                        value: 'ab'
                    },
                    {
                        label: 'O',
                        value: 'o'
                    },
                ],
                signatureType: 'draw',
                loading: false,
                postFormDialog: false
            }
        },
        computed: {
            apiUrl() {
                return route('api.employees.get-all-for-table')
            }
        },
        methods: {
            __allcap(value) {
                return value.toUpperCase()
            },
            __formatDate(value, fmt = 'D MMM YYYY') {
                return (value == null) ? '' : moment(value, 'YYYY-MM-DD').format(fmt)
            },
            onPaginationData(paginationData) {
                this.$refs.pagination.setPaginationData(paginationData)
                this.$refs.paginationInfo.setPaginationData(paginationData)
            },
            onChangePage(page) {
                this.$refs.vuetable.changePage(page);
            },
            onFileChange(e) {
                let files = e.target.files || e.dataTransfer.files

                if (!files.length) return

                this.createImage(_.first(files))
            },
            createImage(file) {
                let reader = new FileReader()

                reader.onload = (e) => {
                    this.employee.image = e.target.result
                };

                reader.readAsDataURL(file)
            },
            removeImage() {
                this.employee.image = null
            },
            openPostFormDialog() {
                this.postFormDialog = true
                this.resetFields()
            },
            closePostFormDialog() {
                this.postFormDialog = false
                this.resetFields()          
            },
            submit() {
                let signature = this.$refs.signaturePad.saveSignature()
                
                if (!signature.isEmpty) {
                    this.employee.signature = signature.data
                }

                axios.post(route('api.employees.post'), this.employee)
                    .then(({data}) => {
                        this.loading = false
                        
                        if (!data.success) {
                            this.errors = data.errors
                            return false
                        }
                        
                        this.resetFields()
                        this.$nextTick(() => this.$refs.vuetable.refresh())
                        this.postFormDialog = false

                        this.$notify({
                            title: 'Success',
                            message: 'You have successfully added the employee.',
                            type: 'success'
                        });
                    })
                    .catch((error) => {
                        this.loading = false
                    })
            },
            resetFields() {
                _.each(this.employee, (value, key) => {
                    this.employee[key] = null
                })
            }
        },
        events: {
            'filter-set' (filterText) {
                this.moreParams = {
                    filter: filterText
                }

                this.$nextTick(() => this.$refs.vuetable.refresh())
            },
            'filter-reset' () {
                this.moreParams = {}
                this.$nextTick(() => this.$refs.vuetable.refresh())
            },
            'edit-item' (data, index) {
                this.postFormDialog = true

                _.each(this.employee, (value, key) => {
                    this.employee[key] = data[key]
                })
            },
            'delete-item' (data, index) {
                this.$confirm('Are you sure you want to delete this item?', 'Warning', {
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'Cancel',
                    type: 'warning'
                }).then(() => {
                    this.loading = true;

                    axios.get(route('api.employees.delete', data.id))
                        .then(({data}) => {
                            this.loading = false;
                            this.$nextTick(() => this.$refs.vuetable.refresh());

                            if (!data.success) {
                               this.errors = data.errors;
                                return false;
                            }

                            this.$notify({
                                title: 'Success',
                                message: 'You have successfully deleted the employee.',
                                type: 'success'
                            });
                        })
                }).catch(() => {
                });
            },
            'download-details' (data, index) {
                window.location.href = route('api.employees.details', data.id) 
            },
        }
    }
</script>

<style lang="scss">
    .signature-field-container div {
        border: 1px solid #dcdfe6;
    }

    .el-dialog__body {
        padding-top: 15px;
        padding-bottom: 15px;
    }

    .el-form-item {
        margin-bottom: 10px;

        .el-form-item__content {
            line-height: normal;
        }

        .el-form-item__content > img {
            display: block;
            width: 200px;
            height: auto;
            margin-bottom: 10px;
        }

        .el-form-item__label {
            margin-bottom: 0;
        }
    }

    .el-form--label-top .el-form-item__label {
        padding-bottom: 0;
    }

    .btn-file {
        position: relative;
        overflow: hidden;

        input[type=file]{
            position: absolute;
            top: 0;
            right: 0;
            min-width: 100%;
            min-height: 100%;
            font-size: 100px;
            text-align: right;
            filter: alpha(opacity = 0);
            opacity: 0;
            outline: none;
            background: white;
            cursor: inherit;
            display: block;
        }
    }

    .vuetable-pagination {
        .pagination-info {
            float: left;
        }

        .vuetable-pagination-info {
            display: inline-block;
        }
    }
</style>