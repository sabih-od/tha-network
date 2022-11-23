<template>
    <div class="modal fade modal_invite" ref="inviteModal" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Send Invitation</h5>
                </div>
                <div class="modal-body">
                    <h5><small>Send invitations to people to join your network</small></h5>
                    <form @submit.prevent="submit">
                        <div class="form-group">
<!--                            <input class="form-control" placeholder="Email" v-model="form.email" :disabled="form.processing">-->
                            <input class="form-control email_inputs" placeholder="Email" v-for="(email_object, key) in email_objects" v-model="email_objects[key].content">

<!--                            <div id="tags">-->
<!--                                <input type="text" value="" placeholder="Add a tag" />-->
<!--                            </div>-->
                        </div>
                        <div class="form-group">
                            <button type="button" class="btn btn-sm btn-success" @click="add_email_object">
                                + Add email
                            </button>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="themeBtn">
                                {{ form.processing ? 'Please wait...' : 'Send' }}
                            </button>
                        </div>
                    </form>
                </div>
                <!--                <div class="modal-footer">-->
                <!--                    <button type="button" class="btn btn-primary" @click.prevent="sendInvite()">Send</button>-->
                <!--                </div>-->
            </div>
        </div>
    </div>
</template>

<script>
import PostMainData from "./PostMainData";
import {useForm, usePage} from "@inertiajs/inertia-vue3";
import {useToast} from "vue-toastification";
import utils from "../mixins/utils";
import {Inertia} from "@inertiajs/inertia";

export default {
    name: "SendInviteModal",
    mixins: [utils],
    components: {
        PostMainData
    },
    computed: {
        user() {
            const page = usePage().component.value
            if (page === 'UserProfile')
                return usePage().props.value?.user ?? null
            return usePage().props.value?.auth ?? null
        },
        name: () => {
            const profile = usePage().props.value?.profile ?? null
            return profile ? profile?.first_name + ' ' + profile?.last_name : ''
        }
    },
    data() {
        return {
            modal: null,
            post: null,
            form: useForm({
                email: '',
                processing: false,
                username: '',
                name: ''
            }),
            email_objects: [
                {
                    content: ''
                }
            ]
        }
    },
    mounted() {
        this.form.username = this.user.username;
        this.form.name = this.name;
        const modalEl = this.$refs.inviteModal
        this.modal = new bootstrap.Modal(modalEl, {
            keyboard: false,
            backdrop: 'static'
        })

        // TAGS BOX
        $(".email_inputs").on({
            focusout() {
                var txt = this.value.replace(/[^a-z0-9@\+\-\.\#]/ig,''); // allowed characters
                var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                // if(txt && regex.test(txt.toLowerCase())) $("<span/>", {text:txt.toLowerCase(), insertBefore:this});
                // this.value = "";
            },
            keyup(ev) {
                if(/(,|Enter)/.test(ev.key)) $(this).focusout();
            }
        });
        $("#tags").on("click", "span", function() {
            $(this).remove();
        });
        // this.$emitter.on('share-post-modal', (post) => {
        //     this.post = post
        //     this.form.post_id = post.id
        //     this.show()
        // })
    },
    methods: {
        show() {
            this.modal.show()
        },
        hide() {
            // this.modal.hide()
            $('.modal_invite').modal('hide');
        },
        submit() {
            if (this.form.processing) return;

            this.form.username = this.user.username;

            const formData = new FormData();
            formData.append('name', this.form.name);
            formData.append('username', this.form.username);

            this.email_objects.forEach(function(email_object) {
                formData.append('emails[]', email_object.content);
            });


            Inertia.post(this.$route('sendInvitation'), formData, {
                replace: true,
                onSuccess: (response) => {
                    console.log(response);
                    setTimeout(() => console.log('asd'), 3000);
                    (useToast()).options = {
                        "showDuration": "3000",
                    };
                    (useToast()).success('The invitation(s) have been sent.');
                    this.hide();
                    this.email_objects = [{content: ''}];
                    this.form.reset()
                    // this.showSuccessMessage()
                },
                onFinish: () => {
                    this.showErrorMessage()
                }
            })
        },
        add_email_object() {
            this.email_objects.push({
                content: ''
            });
        }
    }
}
</script>

<style scoped>
.modal-header {
    background: var(--primary);
}

.modal-title {
    color: #fff;
}

#tags{
    float:left;
    border:1px solid #ccc;
    padding:5px;
    font-family:Arial;
    overflow-wrap: anywhere;
}
#tags > span{
    cursor:pointer;
    display:block;
    float:left;
    color:#fff;
    background:#789;
    padding: 5px 25px 5px 5px;
    margin: 4px 10px 4px 4px;
}
#tags > span:hover{
    opacity:0.7;
}
#tags > span:after{
    position:absolute;
    content:"×";
    border:1px solid;
    padding:2px 5px;
    margin-left:3px;
    font-size:11px;
    margin-right: 10px;
}
#tags > input{
    background:#eee;
    border:0;
    margin:4px;
    padding:7px;
    width:auto;
}
</style>
