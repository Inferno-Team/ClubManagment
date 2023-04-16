<template>
    <div class="full-width">
        <div class="login-container">
            <h2 class="mx-auto top-login-text mb-2">Login</h2>
            <form novalidate @submit.prevent="validateUser">

                <md-field class="mx-auto" id="email-container">
                    <label for="email">Email</label>
                    <md-input name="email" id="email" autocomplete="off" v-model="form.email" :disabled="sending" />
                </md-field>

                <md-field class="mx-auto">
                    <label for="password">Password</label>
                    <md-input name="password" id="password" autocomplete="off" v-model="form.password"
                        :disabled="sending" type="password"/>
                </md-field>
                <md-checkbox class="md-primary" v-model="keepLoggedIn">Keep me logged in</md-checkbox>
                <md-button type="submit" class="md-primary md-raised">Login</md-button>
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
            email: null,
            password: null
        },
        sending: false,
        keepLoggedIn: false,
        errors: {
            email: [],
            password: []
        },
        dialogStates: {
            password: false,
            email: false,
        }
    }),

    methods: {
        validateUser() {
            //send post request to api
            axios.post('/api/login', this.form)
                .then((response) => {
                    let data = response.data;
                    if (data.code == 200) {
                        this.$toast.success(data.msg);
                        localStorage.setItem(CONSTANCES.TOKEN_NAME, data.login.token);
                        localStorage.setItem(CONSTANCES.USER_TYPE, data.login.user.type);
                        if (this.keepLoggedIn) {
                            localStorage.setItem(CONSTANCES.KEEP_LOGGED_IN, true);
                        }
                        axios.defaults.headers.common['Authorization'] = `Bearer ${data.login.token}`;
                        this.$router.push({ name: 'home' });
                    } else {
                        this.$toast.error(data.msg);
                        this.errors = data.errors;
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

        }
    }
}
</script>

<style scoped>
.full-width {

    height: 100vh;
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
    height: calc(100% - 75px);
    top: 25px;
    right: 17%;
    z-index: 2;
}

.md-field {
    width: 22rem;
}

.md-raised {
    width: 20rem;
    margin-left: 6.25rem !important;
}

.top-login-text {
    max-width: fit-content;
    margin-top: 8rem;
}

.md-checkbox {
    margin-left: 5.2rem;
}
</style>
