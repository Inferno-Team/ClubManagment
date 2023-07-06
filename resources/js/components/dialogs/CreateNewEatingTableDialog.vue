<template>
    <md-modal-dialog>
        <md-dialog-title>Create New Eating Table</md-dialog-title>
        <md-dialog-content>
            <md-dialog-title>Diet Details</md-dialog-title>
            <md-field>
                <label>Table Name</label>
                <md-input type="text" v-model="table.name" required />
            </md-field>
        </md-dialog-content>

        <md-dialog-actions>
            <md-button @click="$modal.submit(table)">Add</md-button>
            <md-button @click="$modal.cancel()">Cancel</md-button>
        </md-dialog-actions>
    </md-modal-dialog>
</template>

<script>
export default {


    data: () => ({
        table: {
            name: ''
        }
    }),

}
</script>
<style></style>
