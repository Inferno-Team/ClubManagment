<template>
    <md-modal-dialog v-slot="{ sub }" >
        <md-dialog-title>Edit [ <strong>{{ sub.name }}</strong> ] </md-dialog-title>
        <md-dialog-content>
            <md-field>
                <label>Subscription Name</label>
                <md-input type="text" v-model="sub.name" required />
            </md-field>

        </md-dialog-content>

        <md-dialog-actions>
            <md-button  @click="$modal.submit(sub)">Edit</md-button>
            <md-button @click="$modal.cancel('cancel')">Cancel</md-button>
        </md-dialog-actions>
    </md-modal-dialog>
</template>

<script>
export default {

}
</script>
<style></style>
