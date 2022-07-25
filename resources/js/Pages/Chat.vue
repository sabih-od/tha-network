<template>
    <section class="groupChatSec">
        <div class="container-fluid px-0">
            <div class="row mx-0 no-gutters">
                <ChatListing/>

                <div class="col-md-8">
                    <ChatMessages/>
                </div>

                <div class="col-md-2">
                    <ChatUsersListing/>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import ChatLayout from "../Layouts/ChatLayout";
import ChatListing from "../components/ChatListing";
import ChatMessages from "../components/ChatMessages";
import ChatUsersListing from "../components/ChatUsersListing";

export default {
    name: "Chat",
    layout: ChatLayout,
    components: {
        ChatListing,
        ChatMessages,
        ChatUsersListing
    }
}
</script>

<style scoped>

</style>
