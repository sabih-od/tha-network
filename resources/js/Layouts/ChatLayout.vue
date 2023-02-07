<template>
    <div class="group-chatting">
<!--        <ChatHeader/>-->
        <HowItWorksMenu/>
        <img src="images/banner.jpg" alt="" class="chatBanner">
        <slot></slot>
    </div>
</template>

<script>
import ChatHeader from "../components/ChatHeader";
import HowItWorksMenu from "../components/HowItWorksMenu";

export default {
    name: "ChatLayout",
    components: {
        ChatHeader,
        HowItWorksMenu
    }
}
</script>

<style scoped>

</style>
