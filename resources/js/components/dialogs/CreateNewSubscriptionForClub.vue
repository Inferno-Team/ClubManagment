<template>
    <md-modal-dialog v-slot="{ subs }">
        <md-dialog-title>Create New Subscription</md-dialog-title>
        <md-dialog-content>
            <md-field>
                <label>Subscription Name</label>
                <md-select v-model="selected_sub">
                    <md-option v-for="(sub, index) in subs" :key="index" :value="sub.id">{{ sub.name }}</md-option>
                </md-select>
            </md-field>
            <md-field>
                <label>Subscription Price</label>
                <md-input type="number" v-model="price" required />
            </md-field>

        </md-dialog-content>

        <md-dialog-actions>
            <md-button :disabled="price == null || price == ''"
                @click="$modal.submit({ sub: selected_sub, price: price })">Add</md-button>
            <md-button @click="$modal.cancel()">Cancel</md-button>
        </md-dialog-actions>
    </md-modal-dialog>
</template>

<script>
export default {
    data: () => ({
        selected_sub: {},
        price: null,
    }),
    methods: {

    }
}
</script>
<style></style>
