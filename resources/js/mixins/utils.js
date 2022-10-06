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
            return this.asset('images/char-usr.png')
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
