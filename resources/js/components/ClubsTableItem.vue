<template>
    <md-table-row>
        <md-table-cell md-numeric>{{ club.id }}</md-table-cell>
        <md-table-cell> {{ club.name }} </md-table-cell>
        <md-table-cell> {{ club.location }} </md-table-cell>
        <md-table-cell> {{ club.manager.name }} </md-table-cell>
        <md-table-cell> {{ club.manager.email }} </md-table-cell>
    </md-table-row>
</template>

<script>
export default {
    props: ['club']

}
</script>

<style scoped></style>
