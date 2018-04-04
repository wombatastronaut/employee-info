<template>
    <div class="custom-actions">
        <el-button-group>
            <el-tooltip class="item" effect="dark" content="View" placement="bottom">
                <el-button size="mini" icon="el-icon-zoom-in" @click="itemAction('view-item', rowData, rowIndex)"></el-button>
            </el-tooltip>
            <el-tooltip class="item" effect="dark" content="Edit" placement="bottom">
                <el-button size="mini" icon="el-icon-edit" @click="itemAction('edit-item', rowData, rowIndex)"></el-button>
            </el-tooltip>
            <el-tooltip class="item" effect="dark" content="Download" placement="bottom">
                <el-button size="mini" icon="el-icon-download" @click="itemAction('download-details', rowData, rowIndex)"></el-button>
            </el-tooltip>
            <el-tooltip class="item" effect="dark" content="Delete" placement="bottom">
                <el-button size="mini" icon="el-icon-delete" @click="itemAction('delete-item', rowData, rowIndex)"></el-button>
            </el-tooltip>
        </el-button-group>
    </div>
</template>

<script>
    export default {
        props: {
            rowData: {
            type: Object,
            required: true
            },
            rowIndex: {
            type: Number
            }
        },
        methods: {
            itemAction (action, data, index) {
                this.$events.fire(action, data, index)
            }
        }
    }
</script>

<style lang="scss">
    .custom-actions button.ui.button {
        padding: 8px 8px;
    }

    .custom-actions button.ui.button > i.icon {
        margin: auto !important;
    }
</style>