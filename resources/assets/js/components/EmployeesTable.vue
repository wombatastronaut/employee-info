<template>
    <div id="employees-table">
        <loader :loading="loading"></loader>
        <div class="row">
            <div class="col-md-6">
                <filter-bar></filter-bar>
            </div>
            <div class="col-md-6">
                <el-button class="pull-right" type="primary" style="margin-top: 8px;" @click="postFormDialog = true">Add Employee</el-button>
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
            @vuetable:loading="onLoading"
            @vuetable:loaded="onLoaded"
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
            width="40%">
            <el-form label-position="top" :model="employee">
                <el-form-item label="Name">
                    <el-input v-model="employee.name"></el-input>
                </el-form-item>
                <el-form-item label="Email">
                    <el-input v-model="employee.email"></el-input>
                </el-form-item>
                <el-form-item label="Birthday">
                    <el-date-picker
                        type="date"
                        placeholder=""
                        v-model="employee.birthdate">
                    </el-date-picker>
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
                </el-form-item>
                <el-form-item label="Image">
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
                </el-form-item>
            </el-form>
            <span slot="footer" class="dialog-footer">
                <el-button @click="postFormDialog = false">Cancel</el-button>
                <el-button type="primary" @click="post">Submit</el-button>
            </span>
        </el-dialog>
    </div>
</template>

<script>
    import Vue from 'vue';
    import moment from 'moment';
    import Vuetable from 'vuetable-2/src/components/Vuetable';
    import VuetablePagination from 'vuetable-2/src/components/VuetablePagination';
    import VuetablePaginationInfo from 'vuetable-2/src/components/VuetablePaginationInfo';
    import VueEvents from 'vue-events';
    import VueSignaturePad from 'vue-signature-pad';
    import CustomActions from './EmployeesTableCustomActions';
    import FilterBar from './EmployeesTableFilterBar';
    import { get, post } from '../helpers/http';

    Vue.use(VueEvents);
    Vue.use(VueSignaturePad);

    Vue.component('custom-actions', CustomActions);
    Vue.component('filter-bar', FilterBar);

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
                    name: null,
                    enail: null,
                    birthdate: null,
                    blood_type: null,
                    image: null,
                    signature: null
                },
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
            __allcap (value) {
                return value.toUpperCase()
            },
            __formatDate (value, fmt = 'D MMM YYYY') {
                return (value == null) ? '' : moment(value, 'YYYY-MM-DD').format(fmt)
            },
            onPaginationData (paginationData) {
                this.$refs.pagination.setPaginationData(paginationData)
                this.$refs.paginationInfo.setPaginationData(paginationData)
            },
            onChangePage (page) {
                this.$refs.vuetable.changePage(page);
            },
            onLoading (response) {
                this.loading = true
            },
            onLoaded (response) {
                this.loading = false
            },
            post () {
				post(route('api.employees.post'), { id: 'dsadsa' })
				    .then(({data}) => {
				        this.loading = false
				    })
				    .catch((err) => {
				        this.loading = false
				    })
            }
        },
        events: {
            'filter-set' (filterText) {
                this.moreParams = {
                    filter: filterText
                }
                this.$nextTick(() => this.$refs.vuetable.refresh());
            },
            'filter-reset' () {
                this.moreParams = {}
                this.$nextTick(() => this.$refs.vuetable.refresh());
            },
            'delete-item' (data, index) {
                let vm = this;

                this.$confirm('Are you sure you want to delete this item?', 'Warning', {
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'Cancel',
                    type: 'warning'
                }).then(() => {
                    this.loading = true;

                    get(route('api.employees.delete', data.id))
                        .then(({data}) => {
                            this.loading = false;
                            Vue.nextTick(() => vm.$refs.vuetable.refresh());

                            if(!data.success){
                                this.$notify({
                                    title: 'Warning',
                                    message: 'Failed to delete the employee. Please try again.',
                                    type: 'warning'
                                });
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
            }
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
        margin-bottom: 15px;

        .el-form-item__label {
            margin-bottom: 0;
        }
    }

    .el-form--label-top .el-form-item__label {
        padding-bottom: 0;
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