<template>
    <div class="filter-bar">
        <el-form :inline="true">
            <el-form-item>
                <el-input placeholder="Name or Email" v-model="filterText" @keyup.enter="doFilter"></el-input>
            </el-form-item>
            <el-form-item>
                <el-button type="primary" @click.prevent="doFilter">Search</el-button>
                <el-button @click.prevent="resetFilter">Reset</el-button>
            </el-form-item>
        </el-form>
    </div>
</template>

<script>
    export default {
        data () {
            return {
                filterText: ''
            }
        },
        methods: {
            doFilter () {
                this.$events.fire('filter-set', this.filterText)
            },
            resetFilter () {
                this.filterText = ''
                this.$events.fire('filter-reset')
            }
        }
    }
</script>

<style>
    .filter-bar {
        padding: 10px;
    }
</style>