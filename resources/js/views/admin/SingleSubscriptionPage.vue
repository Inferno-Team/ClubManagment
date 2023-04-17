<template>
    <div>

    </div>
</template>

<script>
export default {
    props: ['id'],
    data: () => ({
        sub: {}
    }),
    mounted() {
        axios.get(`/subscription/api/${this.id}`)
            .then((response) => {
                let data = response.data;
                if (data.sub == null || data.sub == undefined) {
                    this.$toast.error('Subscription Type not found.');
                    setTimeout(() => {
                        this.$router.back(-1);
                    }, 3000);
                } else
                    this.sub = data.sub;
            })
    }
}
</script>

<style scoped></style>
