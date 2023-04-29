<template>
    <div>
        <div class="md-layout md-gutter px-5 py-2 mt-1">
            <div class="md-layout-item md-elevation-2 pt-3">
                <span class="md-title ">This Year Subscriptions</span>
                <canvas style="width: 1200px; height: 230px;" class="mx-auto" id="subscription-chart"></canvas>
            </div>
            <div style="width: 30px; height: 30px;"></div>
            <div class="md-layout-item md-elevation-2 pt-3 md-size-33">

            </div>
        </div>
    </div>
</template>

<script>
import Chart from "chart.js/auto";

export default {
    props: ['id'],
    data: () => ({
        sub: {},
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
    }),
    mounted() {
        axios.get(`/subscription/api/show/${this.id}`)
            .then((response) => {
                let data = response.data;
                if (data.sub == null || data.sub == undefined) {
                    this.$toast.error('Subscription Type not found.');
                    setTimeout(() => {
                        // this.$router.back(-1);
                    }, 3000);
                } else {
                    this.sub = data.sub;
                    this.createSubscriptionChart();
                }
            })
            .catch((error) => {
                console.log(error);
                // this.$router.back(-1);
            })
    },
    methods: {
        createSubscriptionChart() {
            const subscriptions = document.getElementById("subscription-chart");
            this.subscriptionChart.data.labels = this.sub.last_year_subscriptions.keys;
            this.subscriptionChart.data.datasets[0].data = this.sub.last_year_subscriptions.values;
            new Chart(subscriptions, this.subscriptionChart);
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
