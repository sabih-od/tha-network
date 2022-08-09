<template>
    <a :href="profile_url" class="iconWrap"><img :src="render_image(profile_img)"
                                      class="rounded-circle" alt=""></a>
</template>

<script>
export default {
    name: "ProfileImageIconRounded",
    props: {
        profile_img: String,
        user_id: {
            default: null,
            type: String
        }
    },
    computed: {
        render_image() {
            return (url) => {
                return url ?? this.$store.getters['Utils/public_asset']('images/small-character.jpg')
            }
        },
        profile_url() {
            if (this.user_id) {
                return this.$store.getters['Utils/generateProfileLink'](this.user_id)
            }
            return '#'
        }
    }
}
</script>

<style scoped>

</style>
