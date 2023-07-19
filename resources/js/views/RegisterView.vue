<template>
    <div class="full-width">
        <div class="login-container">
            <h2 class="mx-auto top-login-text mb-2">Login</h2>
            <form novalidate @submit.prevent="validateUser">
                <md-field class="mx-auto" id="email-container">
                    <label for="name">Name</label>
                    <md-input name="name" id="name" type="text" autocomplete="off" v-model="form.name"
                        :disabled="sending" />
                </md-field>
                <md-field class="mx-auto" id="email-container">
                    <label for="email">Email</label>
                    <md-input name="email" id="email" type="email" autocomplete="off" v-model="form.email"
                        :disabled="sending" />
                </md-field>
                <md-field class="mx-auto" id="email-container">
                    <label for="phone">Phone (Optional)</label>
                    <md-input name="phone" id="phone" type="phone" autocomplete="off" v-model="form.phone"
                        :disabled="sending" />
                </md-field>
                <md-field class="mx-auto">
                    <label for="password">Password</label>
                    <md-input name="password" id="password" autocomplete="off" v-model="form.password" :disabled="sending"
                        type="password" />
                </md-field>
                <div class="mx-auto drop_down_menu_opener">
                    <md-menu md-direction="bottom-start">
                        <md-button md-menu-trigger>{{ selected_club.name }}</md-button>
                        <md-menu-content>
                            <md-menu-item @click="onClubSelected(club)" v-for="(club, index) in clubs" :key="index">{{
                                club.name }}</md-menu-item>
                        </md-menu-content>
                    </md-menu>
                </div>
                <md-button type="submit" class="md-primary md-raised">Register</md-button>

            </form>

        </div>
        <img class="side-image" src="/images/login-image-2.png">

        <md-dialog-alert :md-active.sync="dialogStates.email" :md-content="errors.email[0]" md-confirm-text="Ok" />
        <md-dialog-alert :md-active.sync="dialogStates.password" :md-content="errors.password[0]" md-confirm-text="Ok" />


    </div>
</template>

<script>
import { CONSTANCES } from '../utils/utils';


export default {

    data: () => ({
        form: {
            name: null,
            email: null,
            phone: null,
            password: null,
            type: 'trainer',
            club_id: -1

        },
        sending: false,
        keepLoggedIn: false,
        clubs: [],
        errors: {
            email: [],
            password: []
        },
        dialogStates: {
            password: false,
            email: false,
        },
        selected_club: {
            name: "Select Club",
            id: -1
        }
    }),

    methods: {
        validateUser() {
            if (this.form.club_id == -1) {
                this.$toast.warning("Please Select Gym Before Register.");
                return;
            }
            //send post request to api
            axios.post('/api/register', this.form)
                .then((response) => {
                    let data = response.data;
                    if (data.code == 200) {
                        this.$toast.success(data.msg);
                        localStorage.setItem(CONSTANCES.TOKEN_NAME, data.login.token);
                        localStorage.setItem(CONSTANCES.USER_TYPE, data.login.user.type);
                        axios.defaults.headers.common['Authorization'] = `Bearer ${data.login.token}`;
                        this.$router.push({ name: 'home' });
                    } else {
                        this.$toast.error(data.msg);
                        this.errors = data.errors;
                        // errors : [password:['required']]
                        if (this.errors.email == undefined) this.errors.email = [];
                        else this.dialogStates.email = true;
                        if (this.errors.password == undefined) this.errors.password = [];
                        else this.dialogStates.password = true;
                    }
                })
                .catch((error) => {
                    console.log(error);
                    // this.$toast.error(error);
                });

        },
        goToRegister() {
            this.$router.push({ name: 'register-page' });
        },
        onClubSelected(club) {
            this.selected_club = {
                name: club.name,
                id: club.id
            }
            this.form.club_id = this.selected_club.id;
        }
    },
    mounted() {
        axios.get('/club/api/clubs')
            .then((res) => {
                let data = res.data;
                this.clubs = data.clubs;
            })
            .catch((error) => {
                console.log(error);
                // this.$toast.error(error);
            });
    }
}
</script>

<style scoped>
.full-width {

    height: 100%;
    width: 100%;
    position: relative;
    overflow: hidden;
    background: linear-gradient(80deg, #E778DB, #FAA7E6, #B5ADF6, #98A5FA, #7D7AE5);
}

.login-container {
    position: absolute;
    left: 25px;
    height: calc(100% - 60px);
    top: 30px;
    bottom: 30px;
    width: calc(50% - 120px);
    background: linear-gradient(135deg, #FCDAE9, #DDD9F9);
    opacity: .8;
    border-radius: 28px;
    z-index: 1;
}

.side-image {
    position: absolute;
    width: 44%;
    top: 8%;
    right: 17%;
    z-index: 2;
}

.md-field {
    width: 70%;
}

.md-raised {
    width: 50%;
    margin-left: 25% !important;
}

.md-raised-2 {
    width: 30%;
    margin-left: 35% !important;
}

.top-login-text {
    max-width: fit-content;
    margin-top: 8rem;
}

.md-checkbox {
    margin-left: 5.2rem;
}

.drop_down_menu_opener {

    max-width: fit-content;
    border-bottom: 1px solid gray;
    margin-bottom: 8px;

}

@media screen and (max-width: 768px) {
    .side-image {
        z-index: 0;
        opacity: 0.75;
        width: 62%;
        margin-right: 1%;
        margin-top: 30%;

    }

    .login-container {
        width: 75%;
        margin-left: 6%;
        margin-top: 25%;
        height: 58%;
    }

    .top-login-text {
        margin-top: 4rem;
    }
}
</style>
