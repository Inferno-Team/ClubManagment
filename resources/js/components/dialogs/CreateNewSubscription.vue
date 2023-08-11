<template>
    <md-modal-dialog>
        <md-dialog-title>Create New Subscription</md-dialog-title>
        <md-dialog-content>
            <md-field>
                <label>Subscription Name</label>
                <md-input @change="validate" type="text" v-model="sub.name" required />
            </md-field>
            <md-field>
                <label>Subscription Duration</label>
                <md-input @change="validate" type="number" onkeypress="return event.charCode >= 48" v-model="sub.duration" required />
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
            duration: ""

        },
        submitState: true
    }),
    methods: {
        validate() {
            // console.log(this.sub);
            this.submitState = !(this.sub.name != '' && this.sub.duration != '');

        }
    }
}
</script>
<style></style>
