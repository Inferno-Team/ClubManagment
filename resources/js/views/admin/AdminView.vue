<template>
    <div class="dashboard">
        <sidebar-menu :menu="menu" :relative="true" @item-click="onItemClick" theme="white-theme">

        </sidebar-menu>
        <div class="my-container">
            <md-progress-spinner class="spinner" v-if="addNewClubStatus" :md-diameter="100" :md-stroke="5"
                md-mode="indeterminate" />
            <router-view v-else />

        </div>
        <div class="floating-container">
            <div class="floating-button" @click.prevent="logout">
                <md-icon class="p-4" >logout</md-icon>
            </div>
        </div>
    </div>
</template>

<script>
import CreateNewClubDialog from '../../components/dialogs/CreateNewClubDialog.vue'
import CreateNewSubscription from '../../components/dialogs/CreateNewSubscription.vue';
import { CONSTANCES } from '../../utils/utils';
export default {
    data: () => ({
        addNewClubStatus: false,
        menu: [
            {
                header: true,
                title: 'Club Managment'
            },
            {

                title: "Clubs",
                href: "/admin/clubs/",
                icon: 'fa fa-bar-chart',
                child: [
                    {
                        title: "Create Club",
                        icon: "fa fa-university",
                    },
                ]

            },

            {
                title: 'Subscriptions',
                icon: 'fa fa-credit-card',
                href: '/admin/subscriptions',
                child: [
                    {
                        title: 'Create New Subscription',
                        icon: 'fa fa-plus-square-o'
                    }
                ]
            }
        ]
    }),
    methods: {
        onItemClick(event, item, node) {

            if (item.title === 'Create Club') {
                this.$modal.show(CreateNewClubDialog)
                    .then(this.club)
                    .catch(error => { });
            } else if (item.title == 'Create New Subscription') {
                this.$modal.show(CreateNewSubscription)
                    .then(this.addNewSubscription)
                    .catch(error => { });
            }
        },
        addNewClub(club) {
            this.addNewClubStatus = true;
            axios.post('/club/api/create_club', club)
                .then((response) => {
                    this.addNewClubStatus = false;
                    let data = response.data;
                    if (data.code == 200 || data.code == 201)
                        this.$toast.success(data.msg);
                    else
                        this.$toast.error(data.msg);
                    // let route = this.$router;
                    // console.log(route);
                })
                .catch((error) => {
                    this.addNewClubStatus = false;
                    console.log(error);
                    this.$toast.error('Error try again later.');
                })
        },
        addNewSubscription(sub) {
            this.addNewClubStatus = true;
            axios.post('/subscription/api/create', sub)
                .then((response) => {
                    this.addNewClubStatus = false;
                    let data = response.data;
                    if (data.code == 200 || data.code == 201)
                        this.$toast.success(data.msg);
                    else
                        this.$toast.error(data.errors['name'][0]);

                })
                .catch((error) => {
                    this.addNewClubStatus = false;
                    console.log(error);
                    this.$toast.error('Error try again later.');
                })
        },
        logout() {
            axios.defaults.headers.common['Authorization'] = ``;
            localStorage.removeItem(CONSTANCES.TOKEN_NAME);
            window.location.href = "/login";
        }
    }
}
</script>

<style >
.dashboard {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100%;
}

.dashboard .my-container {
    width: 100%;
    height: 100%;
    background: #ccc;
}

.v-sidebar-menu .vsm--toggle-btn::after {
    content: "\f03a" !important;
    font-family: "FontAwesome" !important;
}

.v-sidebar-menu .vsm--arrow::after {
    content: "\f060" !important;
    font-family: "FontAwesome" !important;
}

.spinner {
    top: 50%;
    right: 33.34%;
    position: absolute;
}
</style>
