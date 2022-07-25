<template>
    <div class="loading-container">
        <div>
            <h1 class="text-white">Please wait...</h1>
            <div class="progress" style="height: 3px">
                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                     :aria-valuenow="progress"
                     aria-valuemin="0" aria-valuemax="100" :style="`width: ${progress}%`"></div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "ImageUploadingProgress",
    props: {
        progress: 0
    }
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

.loading-container .progress {
    height: 3px;
    min-width: 75vw;
}
</style>
