<template>
    <section class="bg-grey editProfile pt-0">
        <div class="container">
            <div class="row jcc">
                <div class="col-md-10">
                    <h2>Personal Information</h2>
                </div>
                <div class="col-md-10">
                    <BioUpdate/>

                    <InfoUpdate/>

                    <AddressUpdate/>

                    <PasswordUpdate/>

                    <div class="edit-card">
                        <div class="df aic jcsb mb-2">
                            <h2>Payment Method</h2>
                            <div class="df aic gap1">
                                <img src="images/payment2.png" alt="">
                                <a href="#" class="editProBtn">Edit</a>
                            </div>
                        </div>
                        <form class="cardWrap" action="login.php">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="cardName">Name on Card</label>
                                        <input type="text" name="cardName" class="form-control"
                                               placeholder="John Smith">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="cardNo">Card Number</label>
                                        <input type="text" name="cardNo" class="form-control"
                                               placeholder="123 456 7890 789 1234">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="zipcode">Billing Zipcode</label>
                                        <input type="text" name="zipcode" class="form-control" placeholder="007788">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="expiry">Expiration date</label>
                                        <input type="text" name="expiry" class="form-control"
                                               placeholder="123 456 7890 789 1234">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="cvv">CVV Code</label>
                                        <input type="password" name="cvv" class="form-control" placeholder="****">
                                    </div>
                                </div>
                            </div>
                            <div class="df aic jcsb">
                                <a href="#" class="payBtn">Add another payment card</a>
                                <a href="#" class="payBtn" data-toggle="modal" data-target="#attentionModal">stop
                                    recurring payments </a>
                            </div>
                        </form>
                    </div>

                    <div class="btn-group gap1">
                        <button type="submit" class="themeBtn" onclick="window.location.href='login.php">Save</button>
                        <button class="themeBtn discard">Discard Changes</button>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!--    <Main>
            <section>
                <CoverPhoto/>
                <div class="container">
                    <div class="topWrap">
                        <div class="row aic">
                            <div class="col-md-6">
                                <UserInfo/>
                            </div>
                            <div class="col-md-6">
                                <div class="btn-group">
                                    <a href="#" @click.prevent class="themeBtn">personal chat group</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <ProfileLeftSide/>
                        <div class="col-md-9">
                            <div class="profile-wrap">
                                <form @submit.prevent="submit">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group" :class="{'mb-0': form.errors.name}">
                                                <label>Name</label>
                                                <input type="text" v-model="form.name" placeholder="John"
                                                       class="form-control">
                                            </div>
                                            <p v-if="form.errors.name" class="small text-danger text-right">{{
                                                    form.errors.name
                                                }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Email</label>
                                                <input type="email" placeholder="johnsmith88@gmail.com"
                                                       class="form-control" :value="form.email" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group" :class="{'mb-0': form.errors.phone}">
                                                <label>Phone</label>
                                                <input type="text" v-model="form.phone" placeholder="123 456 7890"
                                                       class="form-control">
                                            </div>
                                            <p v-if="form.errors.phone" class="small text-danger text-right">{{
                                                    form.errors.phone
                                                }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group" :class="{'mb-0': form.errors.gender}">
                                                <label>Gender</label>
                                                <select v-model="form.gender" class="form-control">
                                                    <option v-for="(item, ind) in genders" :key="ind" :value="item">{{
                                                            item
                                                        }}
                                                    </option>
                                                </select>
                                            </div>
                                            <p v-if="form.errors.gender" class="small text-danger text-right">{{
                                                    form.errors.gender
                                                }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group" :class="{'mb-0': form.errors.dob}">
                                                <label>Date Of Brith</label>
                                                <input type="date" v-model="form.dob" placeholder="03-05-1995"
                                                       class="form-control">
                                            </div>
                                            <p v-if="form.errors.dob" class="small text-danger text-right">{{
                                                    form.errors.dob
                                                }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group" :class="{'mb-0': form.errors.marital_status}">
                                                <label>Marital Status</label>
                                                <select name="marital" v-model="form.marital_status" class="form-control">
                                                    <option v-for="(item, ind) in marital_statuses" :key="ind"
                                                            :value="item">{{
                                                            item
                                                        }}
                                                    </option>
                                                </select>
                                            </div>
                                            <p v-if="form.errors.marital_status" class="small text-danger text-right">{{
                                                    form.errors.marital_status
                                                }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group" :class="{'mb-0': form.errors.country_of_residence}">
                                                <label>Country of Residence</label>
                                                <input type="text" v-model="form.country_of_residence" placeholder="USA"
                                                       class="form-control">
                                            </div>
                                            <p v-if="form.errors.country_of_residence" class="small text-danger text-right">
                                                {{
                                                    form.errors.country_of_residence
                                                }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group" :class="{'mb-0': form.errors.city}">
                                                <label>City</label>
                                                <input type="text" v-model="form.city" placeholder="New York"
                                                       class="form-control">
                                            </div>
                                            <p v-if="form.errors.city" class="small text-danger text-right">{{
                                                    form.errors.city
                                                }}</p>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group" :class="{'mb-0': form.errors.bio}">
                                                <label>Bio</label>
                                                <textarea v-model="form.bio" placeholder="Enter Bio"
                                                          class="form-control"></textarea>
                                            </div>
                                            <p v-if="form.errors.bio" class="small text-danger text-right">{{
                                                    form.errors.bio
                                                }}</p>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group" :class="{'mb-0': form.errors.personal_links}">
                                                <label>Personal Links</label>
                                                <textarea v-model="form.personal_links" placeholder="Personal Links"
                                                          class="form-control"></textarea>
                                            </div>
                                            <p v-if="form.errors.personal_links" class="small text-danger text-right">{{
                                                    form.errors.personal_links
                                                }}</p>
                                        </div>

                                        <div class="col-md-12 text-center">
                                            <button class="themeBtn">UPDATE PROFILE</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <teleport v-if="form.processing" to="body">
                <FormLoading/>
            </teleport>
        </Main>-->
</template>

<script>
import Main from "../Layouts/Main";
import UserInfo from "../components/UserInfo";
import ProfileLeftSide from "../components/ProfileLeftSide";
import {useForm, usePage, Link} from "@inertiajs/inertia-vue3";
import {useToast} from "vue-toastification";
import FormLoading from "../components/FormLoading";
import CoverPhoto from "../components/CoverPhoto";
import {Inertia} from "@inertiajs/inertia";
import ProfileLayout from "../Layouts/ProfileLayout";
import BioUpdate from "../components/Forms/BioUpdate";
import InfoUpdate from "../components/Forms/InfoUpdate";
import AddressUpdate from "../components/Forms/AddressUpdate";
import PasswordUpdate from "../components/Forms/PasswordUpdate";

export default {
    name: "EditProfile",
    components: {
        AddressUpdate,
        BioUpdate,
        InfoUpdate,
        PasswordUpdate,
        Main,
        UserInfo,
        Link,
        ProfileLeftSide,
        FormLoading,
        CoverPhoto
    },
    layout: ProfileLayout,
    props: {
        user: Object,
        profile: Object
    },
    computed: {
        userProfile() {
            return this.$store.state.Profile?.data
        },
        personalLinks() {
            let data = []
            if (this.userProfile?.personal_links) {
                let chunks = this.userProfile.personal_links.split('\n')
                for (const chunk of chunks) {
                    if (chunk)
                        data.push(chunk)
                }
            }
            return data
        }
    },
    data() {
        return {
            form: useForm({
                name: this.user?.name ?? '',
                email: this.user?.email ?? '',
                phone: this.profile?.phone ?? '',
                gender: this.profile?.gender ?? 'Male',
                dob: this.profile?.dob ?? '',
                marital_status: this.profile?.marital_status ?? 'Single',
                country_of_residence: this.profile?.country_of_residence ?? '',
                city: this.profile?.city ?? '',
                bio: this.profile?.bio ?? '',
                personal_links: this.profile?.personal_links ?? ''
            }),
            genders: [
                'Male',
                'Female',
                'Rather Not Say'
            ],
            marital_statuses: [
                'Single',
                'Married',
                'Divorced',
                'Widowed',
                'Complicated'
            ]
        }
    },
    mounted() {
        this.$store.commit('Profile/setProfile', this.profile);
        //hide message button
        $('.btn_message').prop('hidden', true);
        $('.btn_edit_avatar').prop('hidden', false);
        $('.changePhoto').prop('hidden', false);

        $('.btn_add_friend').prop('hidden', true);
        $('.btn_accept_request').prop('hidden', true);
        $('.btn_reject_request').prop('hidden', true);
        $('.btn_unfriend').prop('hidden', true);
        $('.btn_block').prop('hidden', true);

    },
    unmounted() {
        //un-hide message button
        $('.btn_message').prop('hidden', false);
        $('.btn_edit_avatar').prop('hidden', true);
        $('.changePhoto').prop('hidden', true);
    },
    methods: {
        submit() {
            if (this.form.processing) return;
            this.form.post(this.$route('updateProfile'), {
                replace: true,
                onSuccess: () => {
                    (useToast()).clear();
                    (useToast()).success(usePage().props.value?.flash?.success ?? 'Profile updated successfully!');
                    this.$store.commit('Profile/setProfile', {
                        phone: this.profile?.phone,
                        gender: this.profile?.gender,
                        dob: this.profile?.dob,
                        marital_status: this.profile?.marital_status,
                        country_of_residence: this.profile?.country_of_residence,
                        city: this.profile?.city,
                        bio: this.profile?.bio,
                        personal_links: this.profile?.personal_links
                    })
                }
            })
        }
    }
}
</script>

<style scoped>

</style>
