<template>
<!--    <form action="#" @submit.prevent="submit">-->
<!--        <div class="searchBox">-->
<!--            <input type="text" v-model="search" placeholder="Search" name="search" id="search" autocomplete="off">-->
<!--            <button><i class="fal fa-search"></i></button>-->
<!--        </div>-->
<!--    </form>-->
    <form action="#" @submit.prevent="submit">
        <div class="search-box">
            <div class="input-wrapper">
                <input type="text" v-model="search" placeholder="Search Contact" name="search" id="search" autocomplete="off">
            </div>
        </div>
    </form>
</template>

<script>
export default {
    name: "ChatSearchForm",
    data() {
        return {
            search: ''
        }
    },
    methods: {
        submit() {
            this.$emit('search', this.search);
        }
    }
}
</script>

<style scoped>
.group-chatting .searchBox input {
    color: white;
}
</style>
