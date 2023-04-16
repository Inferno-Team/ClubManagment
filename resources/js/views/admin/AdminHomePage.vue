<template>
    <div>
        <md-progress-spinner class="spinner" v-if="isLoading" :md-diameter="100" :md-stroke="5" md-mode="indeterminate" />
        <md-table v-else v-model="searched" md-card md-sort="name" md-sort-order="asc" md-fixed-header
            @md-selected="onClubSelected" style="height: 100vh;">

            <md-table-toolbar>
                <div class="md-toolbar-section-start">
                    <h1 class="md-title">Clubs</h1>
                </div>

                <md-field md-clearable class="md-toolbar-section-end">
                    <md-input placeholder="Search by name..." v-model="search" @input="searchOnTable" />
                </md-field>
            </md-table-toolbar>

            <md-table-empty-state md-label="No clubs found"
                :md-description="`No club found for this '${search}' query. Try a different search term or create a new club.`">
                <md-button class="md-primary md-raised" @click="showAddNewClubModal">Create New Club</md-button>
            </md-table-empty-state>
            <md-table-row slot="md-table-row" slot-scope="{ item }">
                <md-table-cell md-label="ID" md-numeric>{{ item.id }}</md-table-cell>
                <md-table-cell md-label="Name">{{ item.name }}</md-table-cell>
                <md-table-cell md-label="Location">{{ item.location }}</md-table-cell>
                <md-table-cell md-label="Manager Name">{{ item.manager.name }}</md-table-cell>
                <md-table-cell md-label="Manager Email">{{ item.manager.email }}</md-table-cell>
                <md-table-cell md-label="Actions">
                    <md-button @click="showEditNewClubModal(item)" class="md-icon-button">
                        <md-icon>edit</md-icon>
                    </md-button>
                    <md-button class="md-icon-button">
                        <md-icon>delete</md-icon>
                    </md-button>

                </md-table-cell>

            </md-table-row>

            <!-- <md-table-pagination :md-page-size="2" :md-page-options="[1, 2, 3, 4, 5, 6]" :md-update="updatePagination"
                :md-data="users" :md-paginated-data.sync="paginatedClubs" /> -->
        </md-table>
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
        paginatedClubs: [],
        isLoading: true,
    }),
    methods: {
        getAllClubs() {
            this.isLoading = true;
            axios.get('/club/api/clubs')
                .then((response) => {
                    let data = response.data;
                    if (data.code == 200) {
                        this.clubs = data.clubs;
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

        },

        showEditNewClubModal(oldClub) {
            this.$modal.show(EditClubDialog, {
                club: oldClub
            })
                .then(newClub => console.log(newClub))
                .catch(error => { });
        },
        updatePagination(page, pageSize, sort, sortOrder) {
            console.log('pagination has updated', page, pageSize, sort, sortOrder);
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
