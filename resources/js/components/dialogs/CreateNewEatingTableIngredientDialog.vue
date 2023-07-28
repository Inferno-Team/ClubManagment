<template>
    <md-modal-dialog>
        <md-dialog-title>Create New Eating Ingredient</md-dialog-title>
        <md-dialog-content>
            <md-dialog-title>Diet Ingredient</md-dialog-title>
            <md-field>
                <label>Ingredient</label>
                <md-input type="text" v-model="table.ingredient" required />
            </md-field>
            <md-field>
                <label>Quantity</label>
                <md-input type="text" v-model="table.quantity" required />
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
            ingredient: '',
            quantity: '',
        }
    }),

}
</script>
<style></style>
