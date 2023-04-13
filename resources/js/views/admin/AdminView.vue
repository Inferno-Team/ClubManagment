<template>
    <div class="dashboard">
        <sidebar-menu :menu="menu" :relative="true" @item-click="onItemClick" />
        <div class="my-container">
            <md-progress-spinner class="spinner" v-if="addNewClubStatus" :md-diameter="100" :md-stroke="5" md-mode="indeterminate" />
            <router-view v-else />
        </div>
    </div>
</template>

<script>
import CreateNewClubDialog from '../../components/dialogs/CreateNewClubDialog.vue'
export default {
    data: () => ({
        addNewClubStatus: false,
        menu: [
            {
                header: false,
                title: "Dashboard",
                icon: "fa fa-bars",
                href: "/admin/",
            },
            {
                title: "Create Club",
                icon: "fa fa-university",
            },
        ]
    }),
    methods: {
        onItemClick(event, item, node) {
            if (item.title === 'Create Club') {
                this.$modal.show(CreateNewClubDialog)
                    .then(this.addNewClub)
                    .catch(response => { });
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
                })
                .catch((error) => {
                    this.addNewClubStatus = false;
                    console.log(error);
                    this.$toast.error('Error try again later.');
                })
        }
    }
}
</script>

<style scoped>
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
.spinner{
    top: 50%;
    right: 33.34%;
    position: absolute;
}
</style>
