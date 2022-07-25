<template>
    <div class="group-chatting">
        <ChatHeader/>
        <slot></slot>
    </div>
</template>

<script>
import ChatHeader from "../components/ChatHeader";

export default {
    name: "ChatLayout",
    components: {
        ChatHeader
    }
}
</script>

<style scoped>

</style>