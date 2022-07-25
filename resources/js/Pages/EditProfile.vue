<template>
    <Main>
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
    </Main>
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

export default {
    name: "EditProfile",
    components: {
        Main,
        UserInfo,
        Link,
        ProfileLeftSide,
        FormLoading,
        CoverPhoto
    },
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
        this.$store.commit('Profile/setProfile', this.profile)
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
