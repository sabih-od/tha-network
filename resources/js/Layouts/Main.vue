<template>
    <div>
        <Header />
        <slot></slot>
    </div>
</template>

<script>
import Header from "../components/Header";
export default {
    name: "Main",
    components: {Header}
}
</script>

<style scoped>

</style>
