<template>
    <div class="filter-bar">
        <form class="form-inline">
            <div class="form-group">
                <label>Search:</label>
                <input type="text" v-model="filterText" class="form-control" @keyup="doFilter" placeholder="Name or Email">
            </div>
        </form>
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
                setTimeout(() => {
                    this.$events.fire('filter-set', this.filterText)
                }, 1000)
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