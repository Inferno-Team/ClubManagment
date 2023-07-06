<template>
    <div>
        <div class="md-layout md-gutter px-5 py-2 mt-1">
            <div class="md-layout-item md-elevation-2 pt-3">
                <span class="md-title ">This Year Subscriptions</span>
                <canvas style="width: 1200px; height: 230px;" class="mx-auto" id="subscription-chart"></canvas>
            </div>
            <div style="width: 30px; height: 30px;"></div>
            <div class="md-layout-item md-elevation-2 pt-3 md-size-33">
                <span class="md-title ">Customer Count</span>
                <canvas style="width: 230px; height: 230px;" class="mx-auto" id="customer-count-chart"></canvas>
                <span class="md-title ">Customer Count</span>
                <canvas style="width: 230px; height: 230px;" class="mx-auto" id="customer-count-chart"></canvas>
            </div>
        </div>
        <div class="md-layout md-gutter px-5 py-2">
            <md-table class="md-layout-item md-elevation-2 pt-3" v-if="sub != null" v-model="paginatedCustomers" md-card
                md-sort="id" md-sort-order="asc">
                <md-table-toolbar>
                    <div class="md-toolbar-section-start">
                        <h1 class="md-title">Last Customers</h1>
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

                        <md-button @click="showDeleteCustomerSubscriptionDialog(item)" class="md-icon-button">
                            <md-icon>delete</md-icon>
                        </md-button>

                    </md-table-cell>
                </md-table-row>
                <md-table-pagination :md-page-size="10" :md-data="customers" :md-paginated-data.sync="paginatedCustomers" />

            </md-table>
            <md-dialog-confirm :md-active.sync="remove_subscription_dialog_active" md-title="Removing Subscription"
                :md-content="`Do you really want to remove subscription of <strong>${remove_subscription.customer.name}</strong> ?`"
                md-confirm-text="Yes" md-cancel-text="No" @md-cancel="() => remove_subscription = emptySubscription"
                @md-confirm="onSubscriptionRemoveConfirm" />
        </div>
        <div class="md-layout md-gutter px-5 py-2">
            <md-table class="md-layout-item md-elevation-2 pt-3" v-if="sub != null" v-model="paginatedCustomers" md-card
                md-sort="id" md-sort-order="asc">
                <md-table-toolbar>
                    <div class="md-toolbar-section-start">
                        <h1 class="md-title">Last Customers</h1>
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

                        <md-button @click="showDeleteCustomerSubscriptionDialog(item)" class="md-icon-button">
                            <md-icon>delete</md-icon>
                        </md-button>

                    </md-table-cell>
                </md-table-row>
                <md-table-pagination :md-page-size="10" :md-data="customers" :md-paginated-data.sync="paginatedCustomers" />

            </md-table>
            <md-dialog-confirm :md-active.sync="remove_subscription_dialog_active" md-title="Removing Subscription"
                :md-content="`Do you really want to remove subscription of <strong>${remove_subscription.customer.name}</strong> ?`"
                md-confirm-text="Yes" md-cancel-text="No" @md-cancel="() => remove_subscription = emptySubscription"
                @md-confirm="onSubscriptionRemoveConfirm" />
        </div>
    </div>
</template>

<script>
import Chart from "chart.js/auto";

export default {
    props: ['id'],
    data: () => ({
        sub: {},
        emptySubscription: {
            customer: {}
        },
        subscriptionChart: {
            type: "line",
            data: {
                labels: [],
                datasets: [
                    {
                        lineTension: 0.4,
                        label: "Subscriptions of this year",
                        data: [],
                        backgroundColor: [
                            "#4444442d",
                        ],
                        borderColor: ['#444444'],
                        borderWidth: 2,
                        fill: true,
                    },
                ],
            },
            display: false,
        },
        customersCountChart: {
            type: "bar",
            data: {
                labels: ['All Customers', 'Customers Of this month'],
                datasets: [
                    {
                        label: "Count of all customers",
                        data: [],
                        backgroundColor: [
                            "rgba(255, 99, 132, 0.2)",
                            "rgba(54, 162, 235, 0.2)",

                        ],
                        borderColor: [
                            "rgba(255, 99, 132, 1)",
                            "rgba(54, 162, 235, 1)",
                        ],
                        borderWidth: 1,
                    },
                ],
            },
            display: false,
        },
        searchCustomer: null,
        paginatedCustomers: [],
        customers: [],
        remove_subscription_dialog_active: false,
        remove_subscription: {
            customer: {}
        },
    }),
    mounted() {
        axios.get(`/club/api/my-single-club-subscription/${this.id}`)
            .then((response) => {
                let data = response.data;

                if (data.sub == null || data.sub == undefined || data.code > 200) {
                    this.$toast.error('Subscription Type not found.');
                    setTimeout(() => {
                        // this.$router.back(-1);
                    }, 3000);
                } else {
                    this.sub = data.sub;
                    this.customers = this.sub.customers;
                    this.createSubscriptionChart();
                    this.createCustomerChart(
                        this.sub.user_subscriptions_count,
                        this.sub.this_month_subs,
                    );
                }
            })
            .catch((error) => {
                console.log(error);
                // this.$router.back(-1);
            })
    },
    methods: {
        searchByName(items, term) {
            if (term) {
                return items.filter(item => this.toLower(item.customer.name).includes(this.toLower(term)))
            }

            return items
        },
        toLower(text) {
            return text.toString().toLowerCase();
        },
        searchOnCustomers() {
            this.paginatedCustomers = this.searchByName(this.customers, this.searchCustomer);
        },
        createSubscriptionChart() {
            const subscriptions = document.getElementById("subscription-chart");
            this.subscriptionChart.data.labels = this.sub.user_subscriptions.keys;
            this.subscriptionChart.data.datasets[0].data = this.sub.user_subscriptions.values;
            new Chart(subscriptions, this.subscriptionChart);
        },
        showDeleteCustomerSubscriptionDialog(item) {
            this.remove_subscription = item;
            this.remove_subscription_dialog_active = true;
        },
        onSubscriptionRemoveConfirm() {
            axios.post('/subscription/api/delete-customer-subscription', {
                id: this.remove_subscription.id,
            })
                .then((res) => {
                    let data = res.data;
                    if (data.code == 200) {
                        this.$toast.success(data.msg);
                        let index = this.customers.indexOf(this.remove_subscription);
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
        },
        createCustomerChart(all, month) {
            this.customersCountChart.data.datasets[0].data = [all, month];
            const ctx = document.getElementById("customer-count-chart");
            new Chart(ctx, this.customersCountChart);
        }
    }
}
</script>

<style scoped>
.md-layout-item {
    height: 280px;
    background-color: white;
    border-radius: 8px;
}
</style>
