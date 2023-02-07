<template>
    <div :class="mainClass">
        <template v-if="isCheckbox">
            <input class="form-check-input" @input="change" type="checkbox" :id="id">
            <label class="form-check-label" :for="id">
                <div class="onlineUser">
                    <ProfileImageIconRounded :profile_img="user?.profile_img" />
                    <a href="#" @click.prevent>
                        <h3>{{ user.name }}</h3>
                    </a>
                </div>
            </label>
        </template>
        <div v-else class="onlineUser" @click.prevent.stop="select">
            <ProfileImageIconRounded :profile_img="user?.profile_img" />
            <a href="#" @click.prevent>
                <h3>{{ user.name }}</h3>
            </a>
        </div>
    </div>
</template>

<script>
import ProfileImageIconRounded from "./ProfileImageIconRounded";

export default {
    name: "SelectUserCheckbox",
    components: {
        ProfileImageIconRounded
    },
    props: {
        user: Object,
        isCheckbox: {
            type: Boolean,
            default: false
        }
    },
    computed: {
        mainClass() {
            return this.isCheckbox ? 'form-check' : ''
        },
        id() {
            return 'checkbox_' + (Date.now().toString(36) + Math.random().toString(36).substr(2))
        },
    },
    methods: {
        change(e) {
            if (e.target.checked)
                this.$emit('checked', this.user.id)
            else
                this.$emit('unchecked', this.user.id)
        },
        select() {
            this.$emit('select', this.user.id)
        }
    }
}
</script>

<style scoped>
.form-check {
    display: flex;
    align-items: center;
}

.form-check-label {
    flex-grow: 1;
}
</style>
