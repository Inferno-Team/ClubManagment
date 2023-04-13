<template>
    <div class="dashboard">
        <sidebar-menu :menu="menu" :rtl="true" :relative="true" />
        <div class="my-container">
            <router-view />
        </div>
    </div>
</template>

<script>
export default {
    data: () => ({
        menu: [
            {
                header: false,
                title: "Dashboard",
                icon: "fa fa-bars",
                href: "/admin/",
            },
            {
                title: "Create Manager",
                icon: "fa fa-university",
                href: "/admin/create-new-manager",
            },
        ]
    })
}
</script>

<style scoped>
.dashboard {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100%;
}

.dashboard .my-container {
    width: 100%;
    height: 100%;
    background: #ccc;
}

.v-sidebar-menu .vsm--toggle-btn::after {
    content: "\f03a" !important;
    font-family: "FontAwesome" !important;
}

.v-sidebar-menu .vsm--arrow::after {
    content: "\f060" !important;
    font-family: "FontAwesome" !important;
}
</style>
