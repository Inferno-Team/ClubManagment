<template>
    <div></div>
</template>

<script>
import { CONSTANCES, TYPES } from '../utils/utils';
export default {
    mounted() {
        let token = localStorage.getItem(CONSTANCES.TOKEN_NAME);
        let userType = localStorage.getItem(CONSTANCES.USER_TYPE);
        console.log(userType);
        if (token == undefined || token == null)
            this.$router.push({ name: 'login' })
        else {
            switch (userType) {
                case TYPES.ADMIN:
                    this.$router.push({ name: 'admin-page' })
                    break;
                case TYPES.MANAGER:
                    this.$router.push({ name: 'manager-page' });
                    break;
                default:
                    this.$router.push({ name: '404-page' })
                    break;
            }
        }
    }
}
</script>

<style scoped></style>
