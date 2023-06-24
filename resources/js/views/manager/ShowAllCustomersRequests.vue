<template>
    <div>
        <div class="md-layout md-gutter px-5 py-2 height-94">
            <md-table class="md-layout-item md-elevation-2 pt-3" v-model="paginatedCustomers" md-card md-sort="id"
                md-sort-order="asc">
                <md-table-toolbar>
                    <div class="md-toolbar-section-start">
                        <h1 class="md-title">Last Requests</h1>
                    </div>


                    <md-field md-clearable class="md-toolbar-section-end">
                        <md-input placeholder="Search by name..." v-model="searchCustomer" @input="searchOnCustomers" />
                    </md-field>
                </md-table-toolbar>
                <md-table-empty-state md-label="No subscriptions found"
                    :md-description="`No subscription found . Try a different customer name.`">
                </md-table-empty-state>

                <md-table-row slot="md-table-row" slot-scope="{ item }">
                    <md-table-cell md-label="ID" md-numeric md-sort-by="id">{{ item.id }}</md-table-cell>
                    <md-table-cell md-label="Customer Name" md-sort-by="customer.name">
                        <p class="my-auto" style="max-width: fit-content;font-weight: bold;">
                            {{ item.customer.name }}</p>
                    </md-table-cell>
                    <md-table-cell md-label="price" md-sort-by="price">{{ item.price }}</md-table-cell>
                    <md-table-cell md-label="Start At" md-sort-by="start_at"> {{ item.start_at }} </md-table-cell>
                    <md-table-cell md-label="End At" md-sort-by="end_at">{{ item.end_at }}</md-table-cell>
                    <md-table-cell md-label="Is valid" md-sort-by="is_valid">{{ item.is_valid }}</md-table-cell>
                    <md-table-cell md-label="Actions">

                        <md-button @click="showApproveCustomerSubscriptionDialog(item)" class="md-icon-button">
                            <md-icon>check</md-icon>
                        </md-button>

                    </md-table-cell>
                </md-table-row>
                <md-table-pagination :md-page-size="10" :md-data="customers" :md-paginated-data.sync="paginatedCustomers" />

            </md-table>
            <md-dialog-confirm :md-active.sync="approve_subscription_dialog_active" md-title="Approve Request Subscription"
                :md-content="`Do you really want to approve request subscription of <strong>${approve_subscription.customer.name}</strong> ?`"
                md-confirm-text="Yes" md-cancel-text="No" @md-cancel="() => approve_subscription = emptySubscription"
                @md-confirm="onSubscriptionApproveConfirm" />
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            searchCustomer: null,
            paginatedCustomers: [],
            customers: [],
            searchOnCustomers: {},
            approve_subscription_dialog_active: false,
            approve_subscription: {
                customer: {}
            },
            emptySubscription: {
                customer: {}
            },
        };
    },
    methods: {
        showApproveCustomerSubscriptionDialog(item) {
            this.approve_subscription = item;
            this.approve_subscription_dialog_active = true;
        },
        onSubscriptionApproveConfirm() {
            axios.post('/subscription/api/approve-customer-subscription', {
                id: this.approve_subscription.id,
            })
                .then((res) => {
                    let data = res.data;
                    if (data.code == 200) {
                        this.$toast.success(data.msg);
                        let index = this.customers.indexOf(this.approve_subscription);
                        if (index > -1) {
                            this.customers.splice(index, 1);
                            this.paginatedCustomers = this.customers;
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
    },
    mounted() {
        axios.get('/api/get-all-manager-requests')
            .then((res) => {
                let data = res.data;
                if (data.code == 200) {
                    this.customers = data.requests;
                    this.paginatedCustomers = this.customers;
                }
            })
            .catch(console.error);
    }
}
</script>

<style scoped>
.height-94 {
    height: 94vh;
    overflow-y: auto;
}
</style>
