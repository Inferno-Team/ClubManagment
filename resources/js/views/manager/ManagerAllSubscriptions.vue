<template>
    <div>
        <md-progress-spinner class="spinner" v-if="isLoading" :md-diameter="100" :md-stroke="5" md-mode="indeterminate" />
        <md-table v-else v-model="searched" md-card md-fixed-header style="height: 100vh;" md-sort="id" md-sort-order="asc">

            <md-table-toolbar>
                <div class="md-toolbar-section-start">
                    <h1 class="md-title">Subscriptions</h1>
                </div>

                <md-field md-clearable class="md-toolbar-section-end">
                    <md-input placeholder="Search by name..." v-model="search" @input="searchOnTable" />
                </md-field>
            </md-table-toolbar>

            <md-table-empty-state md-label="No subscriptions found"
                :md-description="`No subscription found quere. Try a different search term or create a new Subscription.`">
                <md-button @click="showAddNewSubscriptionModal" class="md-primary md-raised">Create New
                    Subscription</md-button>
            </md-table-empty-state>
            <md-table-row slot="md-table-row" slot-scope="{ item }">
                <md-table-cell md-label="ID" md-numeric md-sort-by="id">{{ item.id }}</md-table-cell>
                <md-table-cell md-label="Name" md-sort-by="subscription.name">
                    <p class="my-auto" style="cursor: pointer;max-width: fit-content;font-weight: bold;"
                        @click="onSubscriptionSelected(item)">
                        {{ item.subscription.name }}</p>
                </md-table-cell>
                <md-table-cell md-label="Price" md-sort-by="price">{{ item.price }}</md-table-cell>
                <md-table-cell md-label="Number Of Subscriptions" md-sort-by="user_subscriptions_count">{{
                    item.user_subscriptions_count }}</md-table-cell>
                <md-table-cell md-label="Actions">
                    <md-button class="md-icon-button" @click="showEditSubModal(item)">
                        <md-icon>edit</md-icon>
                    </md-button>
                    <md-button class="md-icon-button" @click="showDeleteSubDialog(item)">
                        <md-icon>delete</md-icon>
                    </md-button>
                </md-table-cell>
            </md-table-row>

        </md-table>
        <md-dialog-confirm :md-active.sync="remove_sub_dialog_active" md-title="Removing Subscription Type"
            :md-content="`Do you really want to remove <strong>${remove_sub.subscription.name}</strong> ?`"
            md-confirm-text="Yes" md-cancel-text="No" @md-cancel="() => remove_sub = empty_sub"
            @md-confirm="onSubRemoveConfirm" />
    </div>
</template>

<script>
import CreateNewSubscription from '../../components/dialogs/CreateNewSubscription.vue'
import EditSubscriptionDialog from '../../components/dialogs/EditSubscriptionDialog.vue'
const toLower = text => {
    return text.toString().toLowerCase()
}

const searchByName = (items, term) => {
    if (term) {
        return items.filter(item => toLower(item.subscription.name).startsWith(toLower(term)))
    }

    return items
}

export default {
    mounted() {
        this.getAllSubscriptions();

    },

    data: () => ({
        search: null,
        searched: [],
        subs: [],
        subsCopy: [],
        isLoading: true,
        remove_sub: {
            subscription: {}
        },
        empty_sub: {
            subscription: {}
        },
        remove_sub_dialog_active: false,
    }),
    methods: {
        getAllSubscriptions() {
            this.isLoading = true;
            axios.get('/subscription/api/get-all-subscriptions')
                .then((response) => {
                    let data = response.data;
                    if (data.code == 200) {
                        this.subs = data.subscriptions;
                        this.searched = this.subs;
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
            this.searched = searchByName(this.subs, this.search)
        },
        handleError(error) {
            this.$toast.error("Error please try again later.");
            console.error(error);
        },
        showDeleteSubDialog(sub) {
            this.remove_sub = sub;
            this.remove_sub_dialog_active = true;
        },
        showEditSubModal(oldSub) {
            this.subsCopy = JSON.parse(JSON.stringify(this.subs));
            this.$modal.show(EditSubscriptionDialog, {
                sub: oldSub
            })
                .then(sub => {
                    // send edit sub to server
                    axios.post('/subscription/api/edit', {
                        id: sub.id,
                        name: sub.name,

                    }).then((res) => {
                        let data = res.data;
                        if (data.code == 200) {
                            let editSub = data.sub;
                            let index = this.clubs.indexOf(oldSub);
                            if (index > -1) {
                                this.subs[index] = sub;
                                this.searched = this.subs;
                            }
                            this.$toast.success(data.msg);
                        } else {
                            this.$toast.warning(data.errors['name'][0]);
                        }
                    })
                        .catch(this.handleError);
                })
                .catch((_) => {
                    this.subs = [... this.subsCopy];
                    this.searched = this.subs;
                })
        },
        onSubscriptionSelected(item) {

            this.$router.push({
                name: 'manager-subscription',
                params: { id: item.id }
            })
        },
        onSubRemoveConfirm() {
            axios.post('/subscription/api/delete', {
                id: this.remove_sub.id,
            })
                .then((res) => {
                    let data = res.data;
                    if (data.code == 200) {
                        this.$toast.success(data.msg);
                        let index = this.subs.indexOf(this.remove_sub);
                        if (index > -1) {
                            this.subs.splice(index, 1);
                            this.searched = this.subs;
                        } else {
                            this.$toast.warning('Subscription not found');
                        }
                    } else {
                        this.$toast.warning(data.msg);
                    }
                })
                .catch(this.handleError)
        },
        showAddNewSubscriptionModal() {
            this.$modal.show(CreateNewSubscription)
                .then(this.addNewSubscription)
                .catch(error => { });
        },
        addNewSubscription(sub) {
            axios.post('/subscription/api/create', sub)
                .then((response) => {
                    let data = response.data;
                    if (data.code == 200 || data.code == 201)
                        this.$toast.success(data.msg);
                    else
                        this.$toast.error(data.errors['name'][0]);
                    this.getAllSubscriptions();
                })
                .catch((error) => {
                    console.log(error);
                    this.$toast.error('Error try again later.');
                })
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
