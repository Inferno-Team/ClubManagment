<template>
    <md-modal-dialog v-slot="{ item }" >
        <md-dialog-title>Edit Eating Ingredient</md-dialog-title>
        <md-dialog-content>
            <md-dialog-title>Diet Ingredient</md-dialog-title>
            <md-field>
                <label>Ingredient</label>
                <md-input type="text" v-model="item.ingredient" required />
            </md-field>
            <md-field>
                <label>Quantity</label>
                <md-input type="text" v-model="item.quantity" required />
            </md-field>

        </md-dialog-content>

        <md-dialog-actions>
            <md-button @click="$modal.submit(item)">Edit</md-button>
            <md-button @click="$modal.cancel()">Cancel</md-button>
        </md-dialog-actions>
    </md-modal-dialog>
</template>

<script>
export default {

}
</script>
<style></style>
