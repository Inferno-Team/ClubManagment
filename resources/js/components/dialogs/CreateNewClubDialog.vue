<template>
    <md-modal-dialog>
        <md-dialog-title>Create New Club</md-dialog-title>
        <md-dialog-content>
            <md-dialog-title>Club Details</md-dialog-title>
            <md-field>
                <label>Club Name</label>
                <md-input @change="validate" type="text" v-model="club.name" required />
            </md-field>
            <md-field>
                <label>Club Image</label>
                <md-file v-model="club.image_name" accept="image/*" @md-change="onFileChange" />
            </md-field>
            <md-field>
                <label>Club Location</label>
                <md-input @change="validate" type="text" v-model="club.location" required />
            </md-field>
            <md-field>
                <label for="club_lat">Location lat</label>
                <md-input id="club_lat" @change="validate" type="number" onkeypress="return event.charCode >= 48"
                    v-model="club.lat" required />
            </md-field>
            <md-field>
                <label for="club_lng">Loaction lng</label>
                <md-input id="club_lng" @change="validate" onkeypress="return event.charCode >= 48" type="number" v-model="club.lng" required />
            </md-field>
            <md-dialog-title>Manager Detials</md-dialog-title>
            <md-field>
                <label>Manager Name</label>
                <md-input @change="validate" type="text" v-model="club.manager.name" required />
            </md-field>
            <md-field>
                <label>Manager Email</label>
                <md-input @change="validate" type="email" v-model="club.manager.email" required />
            </md-field>
            <md-field>
                <label>Manager Password</label>
                <md-input @change="validate" type="password" v-model="club.manager.password" required />
            </md-field>

        </md-dialog-content>

        <md-dialog-actions>
            <md-button :disabled="submitState" @click="$modal.submit(club)">Add</md-button>
            <md-button @click="$modal.cancel()">Cancel</md-button>
        </md-dialog-actions>
    </md-modal-dialog>
</template>

<script>
export default {


    data: () => ({
        club: {
            name: "",
            location: '',
            lat: '',
            lng: '',
            image_name: null,
            image: null,
            manager: {
                name: '',
                password: '',
                email: ''
            }
        },
        submitState: true
    }),
    methods: {
        validate() {
            // console.log(this.club);
            this.submitState = !(this.club.name != ''
                && this.club.location != ''
                && this.club.lat != ''
                && this.club.lng != ''
                && this.club.manager.name != ''
                && this.club.manager.password != ''
                && this.club.manager.email != '');

        },
        onFileChange(files) {
            if (files != null && files.length > 0) {
                this.club.image = files[0];
                console.log(this.club.image);
            }
        }
    }
}
</script>
<style></style>
