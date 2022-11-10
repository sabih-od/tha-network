import {Link, usePage} from "@inertiajs/inertia-vue3";

export default {
    components: {
        Link
    },
    computed: {
        asset() {
            return url => this.$store.getters['Utils/public_asset'](url)
        },
        auth_image() {
            const img = usePage().props.value?.auth_profile_image
            if (img !== '' && img !== null)
                return img
            if(user.profile.gender == 'Male') {
                return this.asset('images/avatars/male-avatar.png')
            } else {
                return this.asset('images/avatars/female-avatar.png')
            }
        },
        profile_link() {
            return (id) => {
                return this.$store.getters['Utils/generateProfileLink'](id)
            }
        },
        profile_image() {
            return (image) => {
                if (image !== '' && image)
                    return image
                return this.asset('images/char-usr.png')
            }
        },
        user() {
            return usePage().props.value?.auth ?? null
        }
    },
    methods: {
        showSuccessMessage() {
            this.$store.dispatch('Utils/showSuccessMessage')
        },
        showErrorMessage() {
            this.$store.dispatch('Utils/showErrorMessages')
        }
    }
}
