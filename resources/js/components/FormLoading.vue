<template>
    <div class="loading-container">
        <div>
            <h1 class="text-white text-center">Please wait...</h1>
        </div>
    </div>
</template>

<script>
export default {
    name: "FormLoading",
}
</script>

<style scoped>
.loading-container {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #00000073;
    backdrop-filter: blur(7px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 99999;
}
</style>
