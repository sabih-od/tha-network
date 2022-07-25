import {Link} from "@inertiajs/inertia-vue3";

export default {
    components: {
        Link
    },
    computed: {
        asset() {
            return url => this.$store.getters['Utils/public_asset'](url)
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
