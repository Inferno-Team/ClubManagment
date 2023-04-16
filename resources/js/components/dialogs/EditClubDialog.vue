<template>
    <md-modal-dialog v-slot="{ club }" >
        <md-dialog-title>Edit [ <strong>{{ club.name }}</strong> ] Manager</md-dialog-title>
        <md-dialog-content>
            <md-field>
                <label>Manager Name</label>
                <md-input type="text" v-model="club.manager.name" required />
            </md-field>
            <md-field>
                <label>Manager Email</label>
                <md-input type="email" v-model="club.manager.email" required />
            </md-field>
            <md-field>
                <label>Manager Password</label>
                <md-input type="password" v-model="club.manager.password" required />
            </md-field>

        </md-dialog-content>

        <md-dialog-actions>
            <md-button  @click="$modal.submit(club)">Edit</md-button>
            <md-button @click="$modal.cancel('cancel')">Cancel</md-button>
        </md-dialog-actions>
    </md-modal-dialog>
</template>

<script>
export default {

}
</script>
<style></style>
