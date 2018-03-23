<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <filter-bar></filter-bar>
                <vuetable ref="vuetable"
                    :api-url="apiUrl"
                    :fields="fields"
                    pagination-path=""
                    :css="css.table"
                    :sort-order="sortOrder"
                    :multi-sort="true"
                    :append-params="moreParams"
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
            </div>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue';
    import moment from 'moment';
    import Vuetable from 'vuetable-2/src/components/Vuetable';
    import VuetablePagination from 'vuetable-2/src/components/VuetablePagination';
    import VuetablePaginationInfo from 'vuetable-2/src/components/VuetablePaginationInfo';
    import VueEvents from 'vue-events';
    import CustomActions from './EmployeesTableCustomActions';
    import FilterBar from './EmployeesTableFilterBar';

    Vue.use(VueEvents);

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
                    titleClass: 'text-center',
                    dataClass: 'text-center',
                    callback: 'formatDate|DD-MM-YYYY'
                },
                {
                    name: 'blood_type',
                    title: 'Blood Type',
                    sortField: 'blood_type',
                    callback: 'allcap'
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
                        loadingClass: 'loading',
                        ascendingIcon: 'glyphicon glyphicon-chevron-up',
                        descendingIcon: 'glyphicon glyphicon-chevron-down'
                    },
                    pagination: {
                        wrapperClass: 'pagination',
                        activeClass: 'active',
                        disabledClass: 'disabled',
                        pageClass: 'page',
                        linkClass: 'link',
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
                    { field: 'email', sortField: 'email', direction: 'asc'}
                ],
                moreParams: {}
            }
        },
        computed: {
            apiUrl() {
                return route('api.employees.all');
            }
        },
        methods: {
            allcap (value) {
                return value.toUpperCase();
            },
            formatDate (value, fmt = 'D MMM YYYY') {
                return (value == null) ? '' : moment(value, 'YYYY-MM-DD').format(fmt);
            },
            onPaginationData (paginationData) {
                this.$refs.pagination.setPaginationData(paginationData);
                this.$refs.paginationInfo.setPaginationData(paginationData);
            },
            onChangePage (page) {
                this.$refs.vuetable.changePage(page);
            }
        },
        events: {
            'filter-set' (filterText) {
                this.moreParams = {
                    filter: filterText
                }
                this.$nextTick( () => this.$refs.vuetable.refresh());
            },
            'filter-reset' () {
                this.moreParams = {}
                this.$nextTick( () => this.$refs.vuetable.refresh());
            }
        }
    }
</script>

<style lang="scss">
    .pagination {
        margin: 0;
        float: right;
    }

    .pagination a.page {
        border: 1px solid lightgray;
        border-radius: 3px;
        padding: 5px 10px;
        margin-right: 2px;
    }

    .pagination a.page.active {
        color: white;
        background-color: #337ab7;
        border: 1px solid lightgray;
        border-radius: 3px;
        padding: 5px 10px;
        margin-right: 2px;
    }

    .pagination a.btn-nav {
        border: 1px solid lightgray;
        border-radius: 3px;
        padding: 5px 7px;
        margin-right: 2px;
    }

    .pagination a.btn-nav.disabled {
        color: lightgray;
        border: 1px solid lightgray;
        border-radius: 3px;
        padding: 5px 7px;
        margin-right: 2px;
        cursor: not-allowed;
    }

    .pagination-info {
        float: left;
    }

    .vuetable-pagination-info {
        display: inline-block;
    }
</style>