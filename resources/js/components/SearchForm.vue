<template>
    <form action="#" @submit.prevent="submit">
        <div class="searchBox">
            <input type="text" v-model="search" placeholder="Search" name="search" id="search" autocomplete="off">
            <button><i class="fal fa-search"></i></button>
        </div>
    </form>
</template>

<script>
export default {
    name: "SearchForm",
    data() {
        return {
            search: ''
        }
    },
    methods: {
        submit() {
            this.$emit('search', this.search)
        }
    }
}
</script>

<style scoped>
form {
    margin-bottom: 25px;
    background-color: #0b151e;
}

.searchBox {
    border-radius: 5px;
    background-color: #01060c;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    height: 36px;
}

.searchBox input {
    background-color: transparent;
    border: 0;
    padding-left: 1rem;
    color: white;
    width: 100%;
}

.searchBox input:focus {
    outline: none;
}

.searchBox button {
    color: var(--white);
    font-size: 1rem;
    border: 0;
    padding: 0 1rem;
    background: transparent;
}
</style>