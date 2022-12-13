<template>
<!--    <form action="#" @submit.prevent="submit">-->
<!--        <div class="searchBox">-->
<!--            <input type="text" v-model="search" placeholder="Search" name="search" id="search" autocomplete="off">-->
<!--            <button><i class="fal fa-search"></i></button>-->
<!--        </div>-->
<!--    </form>-->
<!--    <form action="#" @submit.prevent>-->
        <div class="search-box">
            <div class="input-wrapper">
                <input @keyup.enter="submit" type="text" v-model="search" placeholder="Search Contact" name="search" id="search" autocomplete="off">
            </div>
        </div>
<!--    </form>-->
</template>

<script>
export default {
    name: "ChatSearchForm",
    data() {
        return {
            search: ''
        }
    },
    mounted() {
        // $('#search').on('keyup', function (e) {
        //     return e.preventDefault();
        //     // alert(e.key === "Enter");
        // });
    },
    methods: {
        submit() {
            // this.$parent.updateSearchQuery(this.search);
            this.$parent.search_query = this.search;
            this.$parent.loadChatListing();
            // this.$emit('search', this.search);
        }
    }
}
</script>

<style scoped>
.group-chatting .searchBox input {
    color: white;
}
</style>
