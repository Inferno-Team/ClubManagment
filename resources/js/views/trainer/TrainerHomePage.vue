<template>
    <div>
        <md-progress-spinner class="spinner" v-if="isLoading" :md-diameter="100" :md-stroke="5" md-mode="indeterminate" />
        <md-table v-else v-model="searched" md-card md-sort="name" md-sort-order="asc" md-fixed-header
            style="height: 100vh;">

            <md-table-toolbar>
                <div class="md-toolbar-section-start">
                    <h1 class="md-title">Clubs</h1>
                </div>

                <md-field md-clearable class="md-toolbar-section-end">
                    <md-input placeholder="Search by name..." v-model="search" @input="searchOnTable" />
                </md-field>
            </md-table-toolbar>

            <md-table-empty-state md-label="No clubs found"
                :md-description="`No club found quere. Try a different search term or create a new club.`">
                <md-button class="md-primary md-raised" @click="showAddNewClubModal">Create New Club</md-button>
            </md-table-empty-state>
            <md-table-row slot="md-table-row" slot-scope="{ item }">
                <md-table-cell md-label="ID" md-numeric>{{ item.id }}</md-table-cell>
                <md-table-cell md-label="Name">
                    <p class="my-auto" style="cursor: pointer;font-weight: bold;" @click="onClubSelected(item)">{{ item.name
                    }}</p>
                </md-table-cell>
                <md-table-cell md-label="Items Count">{{ item.items_count }}</md-table-cell>
                <md-table-cell md-label="Customer Count">{{ item.customer_count }}</md-table-cell>

                <md-table-cell md-label="Actions">
                    <md-button @click="showEditNewClubModal(item)" class="md-icon-button">
                        <md-icon>edit</md-icon>
                    </md-button>
                    <md-button @click="showDeleteClubDialog(item)" class="md-icon-button">
                        <md-icon>delete</md-icon>
                    </md-button>

                </md-table-cell>

            </md-table-row>

            <!-- <md-table-pagination :md-page-size="2" :md-page-options="[1, 2, 3, 4, 5, 6]" :md-update="updatePagination"
                :md-data="users" :md-paginated-data.sync="paginatedClubs" /> -->
        </md-table>
        <md-dialog-confirm :md-active.sync="remove_club_dialog_active" md-title="Removing Club"
            :md-content="`Do you really want to remove <strong>${remove_club.name}</strong> ?`" md-confirm-text="Yes"
            md-cancel-text="No" @md-cancel="() => remove_club = {}" @md-confirm="onClubRemoveConfirm" />
    </div>
</template>

<script>
import ClubsTableItem from '../../components/ClubsTableItem.vue';
const toLower = text => {
    return text.toString().toLowerCase()
}

const searchByName = (items, term) => {
    if (term) {
        return items.filter(item => toLower(item.name).includes(toLower(term)))
    }

    return items
}
import CreateNewClubDialog from '../../components/dialogs/CreateNewClubDialog.vue'
import EditClubDialog from '../../components/dialogs/EditClubDialog.vue'

export default {
    components: { ClubsTableItem },
    mounted() {
        this.getAllClubs();

    },

    data: () => ({
        search: null,
        searched: [],
        clubs: [],
        clubsCopy: [],
        paginatedClubs: [],
        isLoading: true,
        remove_club: {},
        remove_club_dialog_active: false,
    }),
    methods: {
        getAllClubs() {
            this.isLoading = true;
            axios.get('/trainer/api/get-all-table')
                .then((response) => {
                    let data = response.data;
                    if (data.code == 200) {
                        this.clubs = data.tables;
                        this.searched = this.clubs;
                    }
                    this.isLoading = false;
                })
                .catch((error) => {
                    this.$toast.error("Error");
                    console.error(error);
                    this.isLoading = false;
                });


        },
        searchOnTable() {
            this.searched = searchByName(this.clubs, this.search)
        },
        showAddNewClubModal() {
            this.$modal.show(CreateNewClubDialog)
                .then(this.addNewClub)
                .catch(error => { });
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
                    // re send get request to get all clubs
                    this.getAllClubs();
                })
                .catch((error) => {
                    this.addNewClubStatus = false;
                    console.log(error);
                    this.$toast.error('Error try again later.');
                })
        },
        onClubSelected(club) {
            this.$router.push({
                name: 'single-club',
                params: { id: club.id }
            })
        },

        showEditNewClubModal(oldClub) {
            this.clubsCopy = JSON.parse(JSON.stringify(this.clubs));
            this.$modal.show(EditClubDialog, {
                club: oldClub
            })
                .then(club => {
                    // send edit manager to server
                    axios.post('/api/edit_club_manager', {
                        manager_id: club.manager.id,
                        name: club.manager.name,
                        email: club.manager.email,
                        password: club.manager.password,
                    }).then((res) => {
                        let data = res.data;
                        if (data.code == 200) {
                            let manager = data.manager;
                            let index = this.clubs.indexOf(oldClub);
                            if (index > -1) {
                                this.clubs[index].manager = manager;
                                this.searched = this.clubs;
                            }
                            this.$toast.success(data.msg);
                        } else {
                            this.$toast.warning(data.msg);
                        }
                    })
                        .catch(this.handleError);
                })
                .catch((_) => {
                    this.clubs = [... this.clubsCopy];
                    this.searched = this.clubs;
                })

        },
        updatePagination(page, pageSize, sort, sortOrder) {
            console.log('pagination has updated', page, pageSize, sort, sortOrder);
        },
        showDeleteClubDialog(club) {
            this.remove_club = club;
            this.remove_club_dialog_active = true;
        },
        onClubRemoveConfirm() {
            axios.post('/trainer/api/delete-table', {
                id: this.remove_club.id,
            })
                .then((res) => {
                    let data = res.data;
                    if (data.code == 200) {
                        this.$toast.success(data.msg);
                        let index = this.clubs.indexOf(this.remove_club);
                        if (index > -1) {
                            this.clubs.splice(index, 1);
                            this.searched = this.clubs;
                        } else {
                            this.$toast.warning('Item not found');
                        }
                    } else {
                        this.$toast.warning(data.msg);
                    }
                })
                .catch(this.handleError)
        },
        handleError(error) {
            this.$toast.error("Error please try again later.");
            console.error(error);
        }
    }

}
</script>

<style scoped>
.spinner {
    top: 50%;
    right: 33.34%;
    position: absolute;
}
</style>
