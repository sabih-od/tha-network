<template>
    <form class="edit-card" @submit.prevent="submit">
        <a v-if="!isEdit" href="#" @click.prevent="isEdit = true" class="editProBtn">Edit</a>
        <template v-if="isEdit">
            <div class="d-flex">
                <template v-if="!form.processing">
                    <a href="#" @click.prevent="cancel" class="editProBtn w-auto">Cancel</a>
                    <a href="#" @click.prevent="submit" class="editProBtn ml-2">Save</a>
                </template>
                <a v-else href="#" @click.prevent disabled class="editProBtn w-auto">Saving...</a>
            </div>
        </template>
        <div class="cardWrap">
            <div class="form-group mb-0">
                <label for="bio">bio</label>
                <textarea
                    id="bio"
                    v-model="form.bio"
                    cols="10"
                    class="form-control"
                    placeholder="Enter Bio"
                    :readonly="!isEdit"
                    rows="3"></textarea>
            </div>
        </div>
    </form>
</template>

<script>
import {useForm, usePage} from "@inertiajs/inertia-vue3";

export default {
    name: "BioUpdate",
    data() {
        return {
            isEdit: false,
            form: useForm({
                bio: usePage().props.value?.profile?.bio
            })
        }
    },
    methods: {
        submit() {
            if (this.form.processing) return

            this.form.post(this.$route('updateProfile'), {
                replace: true,
                onSuccess: () => {
                    this.$store.dispatch('Utils/showSuccessMessage')
                },
                onFinish: () => {
                    this.$store.dispatch('Utils/showErrorMessages').then(res => {
                        this.isEdit = false
                    })
                }
            })
        },
        cancel() {
            this.form.bio = usePage().props.value?.profile?.bio
            this.isEdit = false
        }
    }
}
</script>

<style scoped>

</style>
