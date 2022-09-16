<template>
    <!--    <section class="groupChatSec">-->
    <!--        <div class="container-fluid px-0">-->
    <!--            <div class="row mx-0 no-gutters">-->
    <!--                <ChatListing/>-->

    <!--                <div class="col-md-8">-->
    <!--                    <ChatMessages/>-->
    <!--                </div>-->

    <!--                <div class="col-md-2">-->
    <!--                    <ChatUsersListing/>-->
    <!--                </div>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--    </section>-->

    <div class="mainDashboard">
        <div class="slctlft descriptnbg">
            <div class="row messangerSec">
                <ChatListing></ChatListing>

                <div class="col-md-9 col-sm-9 p-0 dse" id="chatbox">
                    <ChatMessages></ChatMessages>
                </div>
            </div>
        </div>


        <!-- Reset Weekly Goals Modal -->
        <div class="modal fade weeklyModal" id="weeklyModal" tabindex="-1" aria-labelledby="weeklyModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <div class="modal-body p-0">
                        <h2>Reset Your Weekly Goals</h2>

                        <form action="">
                            <div class="form-group">
                                <label for="target">Set Target</label>
                                <input type="text" name="target" placeholder="0000" class="form-control">
                            </div>
                            <div class="form-group">
                                <button type="submit" class="themeBtn w-100">UPDATE</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attention Modal -->
        <div class="modal fade attentionModal weeklyModal" id="attentionModal" tabindex="-1"
             aria-labelledby="attentionModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <div class="modal-body p-0 text-center">
                        <h2 class="text-danger">Attention</h2>
                        <p>ATTENTION!!! if you stop recurring payments your profile will be deleted and
                            you will lose all access to the site including referral payments!!! ARE
                            YOU SURE YOU WANT TO STOP RECURRING PAYMENTS???</p>
                        <div class="btn-group gap1 jcc df mt-2">
                            <a href="#" class="themeBtn">YES</a>
                            <a href="#" class="themeBtn blackBtn">NO</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <!--        <script src="js/jquery-3.5.1.min.js"></script>-->
        <!--        <script src="js/popper.min.js"></script>-->
        <!--        <script src="js/bootstrap.min.js"></script>-->
        <!--        <script src="js/jquery.fancybox.min.js"></script>-->
        <!--        <script src="js/slick.min.js"></script>-->
        <!--        <script src="js/wow.min.js"></script>-->
        <!--        <script src="js/emoji.js"></script>-->
        <!--        <script src="js/custom.min.js"></script>-->


    </div>
</template>

<script>
import ChatLayout from "../Layouts/ChatLayout";
import ChatListing from "../components/ChatListing";
import ChatMessages from "../components/ChatMessages";
import ChatUsersListing from "../components/ChatUsersListing";
import {useForm} from "@inertiajs/inertia-vue3";

export default {
    name: "Chat",
    layout: ChatLayout,
    props: {
        profile_id: Object,
    },
    components: {
        ChatListing,
        ChatMessages,
        ChatUsersListing
    },
    mounted() {
        //check for profile_id (gets sent when you click on chat message on profile)
        // if(this.profile_id != null) {
        //     this.$emitter.emit('activate-channel', this.profile_id);
        // }
    }
}
</script>

<style scoped>

</style>
