<template>
    <div>
        <md-progress-spinner class="spinner" v-if="isLoading" :md-diameter="100" :md-stroke="5" md-mode="indeterminate" />
        <md-table v-else v-model="searched" md-card md-sort="name" md-sort-order="asc" md-fixed-header
            style="height: 100vh;">

            <md-table-toolbar>
                <div class="md-toolbar-section-start row-reverse">
                    <h1 class="md-title">Diets Ingredient</h1>
                    <md-button class="md-icon-button" @click="showAddNewClubModal">
                        <md-icon>add</md-icon>
                    </md-button>
                </div>

                <md-field md-clearable class="md-toolbar-section-end">
                    <md-input placeholder="Search by name..." v-model="search" @input="searchOnTable" />
                </md-field>
            </md-table-toolbar>

            <md-table-empty-state md-label="No Ingredients found"
                :md-description="`No Ingredients found quere. Try a different search term or create a new Ingredient.`">
                <md-button class="md-primary md-raised" @click="showAddNewClubModal">Create New Ingredient</md-button>
            </md-table-empty-state>
            <md-table-row slot="md-table-row" slot-scope="{ item }">
                <md-table-cell md-label="ID" md-numeric>{{ item.id }}</md-table-cell>
                <md-table-cell md-label="Ingredient">{{ item.ingredient }}
                </md-table-cell>
                <md-table-cell md-label="Quantity">{{ item.quantity }}</md-table-cell>

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
        <md-dialog-confirm :md-active.sync="remove_club_dialog_active" md-title="Removing Ingredient"
            :md-content="`Do you really want to remove <strong>${remove_club.ingredient}</strong> ?`" md-confirm-text="Yes"
            md-cancel-text="No" @md-cancel="() => remove_club = {}" @md-confirm="onClubRemoveConfirm" />
    </div>
</template>


<script>
const toLower = text => {
    return text.toString().toLowerCase()
}

const searchByName = (items, term) => {
    if (term) {
        return items.filter(item => toLower(item.name).includes(toLower(term)))
    }

    return items
}
import CreateNewEatingTableIngredientDialog from '../../components/dialogs/CreateNewEatingTableIngredientDialog.vue'
import UpdateEatingTableIngredientDialog from '../../components/dialogs/UpdateEatingTableIngredientDialog.vue'

export default {
    props: ['id'],
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
    mounted() {
        this.getIngredients();
    },
    methods: {
        getIngredients() {
            axios.get(`/trainer/api/get-table-ingredient?id=${this.id}`)
                .then((response) => {
                    let data = response.data;
                    if (data.code == 200) {
                        this.clubs = data.items;
                        this.searched = this.clubs;
                    }
                    this.isLoading = false;
                })
                .catch(this.handleError);
        },
        handleError(error) {
            this.$toast.error("Error please try again later.");
            console.error(error);
        },
        searchOnTable() {
            this.searched = searchByName(this.clubs, this.search)
        },
        showAddNewClubModal() {
            this.$modal.show(CreateNewEatingTableIngredientDialog)
                .then(this.createNewEatingTable)
                .catch(error => { });
        },
        createNewEatingTable(table) {
            this.addNewClubStatus = true;
            axios.post('/trainer/api/add-table-ingredient', {
                ...table,
                eat_table_id: this.id
            })
                .then((response) => {
                    this.addNewClubStatus = false;
                    let data = response.data;
                    if (data.code == 200 || data.code == 201)
                        this.$toast.success(data.msg);
                    else
                        this.$toast.warning(data.msg);
                    this.getIngredients();
                })
                .catch((error) => {
                    this.addNewClubStatus = false;
                    console.log(error);
                    this.$toast.error('Error try again later.');
                })
        },
        showEditNewClubModal(oldClub) {
            this.clubsCopy = JSON.parse(JSON.stringify(this.clubs));
            this.$modal.show(UpdateEatingTableIngredientDialog, {
                item: oldClub
            })
                .then(this.editIngredient)
                .catch((e) => {
                    console.error(e);
                    this.clubs = [... this.clubsCopy];
                    this.searched = this.clubs;
                })

        },
        editIngredient(item) {
            this.addNewClubStatus = true;
            // send edit manager to server
            axios.post('/trainer/api/update-table-ingredient', item).then((res) => {
                let data = res.data;
                if (data.code == 200) {
                    this.$toast.success(data.msg);
                } else {
                    this.$toast.warning(data.msg);
                }
            })
                .catch(this.handleError);
        },
        updatePagination(page, pageSize, sort, sortOrder) {
            console.log('pagination has updated', page, pageSize, sort, sortOrder);
        },
        showDeleteClubDialog(club) {
            this.remove_club = club;
            this.remove_club_dialog_active = true;
        },
        onClubRemoveConfirm() {
            axios.post('/trainer/api/delete-table-ingredient', {
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
    }
}
</script>
<style scoped>
.row-reverse {
    flex-direction: row-reverse !important;
}
</style>
