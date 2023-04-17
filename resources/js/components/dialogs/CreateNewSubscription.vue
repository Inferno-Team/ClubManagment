<template>
    <md-modal-dialog>
        <md-dialog-title>Create New Subscription</md-dialog-title>
        <md-dialog-content>
            <md-field>
                <label>Subscription Name</label>
                <md-input @change="validate" type="text" v-model="sub.name" required />
            </md-field>
        </md-dialog-content>

        <md-dialog-actions>
            <md-button :disabled="submitState" @click="$modal.submit(sub)">Add</md-button>
            <md-button @click="$modal.cancel()">Cancel</md-button>
        </md-dialog-actions>
    </md-modal-dialog>
</template>

<script>
export default {


    data: () => ({
        sub: {
            name: "",

        },
        submitState: true
    }),
    methods: {
        validate() {
            // console.log(this.sub);
            this.submitState = !(this.sub.name != '');

        }
    }
}
</script>
<style></style>
