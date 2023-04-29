<template>
    <div>
        <div class="md-layout md-gutter px-5 py-2 mt-1">
            <div class="md-layout-item md-elevation-2 pt-3" >
                <span class="md-title ">This Year Revenue</span>
                <canvas style="width: 1200px; height: 230px;" class="mx-auto" id="revenue-chart"></canvas>
            </div>
            <div style="width: 30px; height: 30px;"></div>
            <div class="md-layout-item md-elevation-2 pt-3 md-size-33" >
                <span class="md-title ">Customer Count</span>
                <canvas style="width: 230px; height: 230px;" class="mx-auto" id="customer-count-chart"></canvas>
            </div>
        </div>


        <div class="md-layout md-gutter px-5 py-2">
            <md-table class="md-layout-item md-elevation-2 pt-3"  v-if="club != null"
                v-model="paginatedCustomers" md-card>
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
                    <md-table-cell md-label="ID" md-numeric>{{ item.id }}</md-table-cell>
                    <md-table-cell md-label="Customer Name">
                        <p class="my-auto" style="max-width: fit-content;font-weight: bold;">
                            {{ item.customer.name }}</p>
                    </md-table-cell>
                    <md-table-cell md-label="price">{{ item.price }}</md-table-cell>
                    <md-table-cell md-label="Start At"> {{ item.start_at }} </md-table-cell>
                    <md-table-cell md-label="End At">{{ item.end_at }}</md-table-cell>
                    <md-table-cell md-label="Is valid">{{ item.is_valid }}</md-table-cell>
                </md-table-row>
                <md-table-pagination :md-page-size="10" :md-data="customers" :md-paginated-data.sync="paginatedCustomers" />

            </md-table>

        </div>
    </div>
</template>

<script>
import Chart from "chart.js/auto";

export default {
    props: ['id'],
    data: () => ({
        club: null,
        searchCustomer: null,
        paginatedCustomers: [],
        customers: [],
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
        revenueChart: {
            type: "line",
            data: {
                labels: [],
                datasets: [
                    {
                        lineTension: 0.4,
                        label: "Revenue of this year",
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

    }),
    mounted() {

        axios.get(`/club/api/show/club/${this.id}`)
            .then((response) => {
                let data = response.data;
                if (data.code == 200) {
                    this.club = data.club;
                    this.customersCountChart.data.datasets[0].data
                        = [
                            this.club.number_of_customer_sub,
                            this.club.number_of_last_month_subs,
                        ];
                    const ctx = document.getElementById("customer-count-chart");
                    this.customers = this.club.customer_sub;
                    this.createRevenueChart();
                    new Chart(ctx, this.customersCountChart);
                }
                else {
                    this.$toast.error('Club Type not found.');
                    setTimeout(() => {
                        this.$router.back(-1);
                    }, 3000);
                }
            })
            .catch((error) => {
                console.log(error);
                this.$router.back(-1);
            })
    },
    computed: {
        mdLayoutItemStyle() {
            let height = (window.innerHeight / 2).toFixed(0) - 27;
            console.log(`height : ${height}`);
            return {
                height: `${height}px`
            }
        },
        // customerStyle(item) {
        //     return {
        //         'background-color': item.is_valid ? 'green' : 'red',
        //     }
        // }
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
        createRevenueChart() {
            const revenue = document.getElementById("revenue-chart");
            this.revenueChart.data.labels = this.club.revenue.keys;
            this.revenueChart.data.datasets[0].data = this.club.revenue.values;
            new Chart(revenue, this.revenueChart);
        },
        searchOnCustomers() {
            this.paginatedCustomers = this.searchByName(this.customers, this.searchCustomer);
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
