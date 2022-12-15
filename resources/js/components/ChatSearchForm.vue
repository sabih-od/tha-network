<template>
<!--    <form action="#" @submit.prevent="submit">-->
<!--        <div class="searchBox">-->
<!--            <input type="text" v-model="search" placeholder="Search" name="search" id="search" autocomplete="off">-->
<!--            <button><i class="fal fa-search"></i></button>-->
<!--        </div>-->
<!--    </form>-->
<!--    <form action="#" @submit.prevent>-->
        <div class="search-box">
            <button :class="search_in_progress ? 'fas ml-4 fa-minus' : 'fas ml-4 fa-search'" @click="toggleSearchInProgress"></button>
            <div class="input-wrapper">
                <input v-if="search_in_progress" @keyup.enter="submit" type="text" v-model="search" placeholder="Search Contact" name="search" id="search" autocomplete="off">
            </div>
        </div>
<!--    </form>-->
</template>

<script>
export default {
    name: "ChatSearchForm",
    data() {
        return {
            search: '',
            search_in_progress: false
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
        },
        toggleSearchInProgress() {
            this.search_in_progress = !this.search_in_progress;
        }
    }
}
</script>

<style scoped>
.group-chatting .searchBox input {
    color: white;
}
</style>
