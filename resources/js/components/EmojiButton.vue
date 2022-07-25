<template>
    <div class="emoji-container">
        <button @click.prevent="toggleDialogEmoji"><i class="fas fa-smile"></i></button>
        <EmojiPicker
            class="emoji-picker"
            v-show="showDialog"
            :options="options"
            @select="selectEmoji"
        />
    </div>
</template>

<script>
import {EmojiPicker} from 'vue3-twemoji-picker-final'

export default {
    name: "EmojiButton",
    components: {
        EmojiPicker
    },
    data() {
        return {
            showDialog: false,
            options: {
                locals: 'en',
                imgSrc: this.$store.getters['Utils/public_asset']('icons/')
            }
        }
    },
    methods: {
        hide() {
            this.showDialog = false
        },
        toggleDialogEmoji() {
            this.showDialog = !this.showDialog;
        },
        selectEmoji(e) {
            this.$emit('selectEmoji', e.imgSrc)
        }
    }
}
</script>

<style scoped>
.emoji-container {
    position: relative;
}

.emoji-container .emoji-picker {
    position: absolute;
    bottom: 55px;
    right: 0;
    min-height: 450px;
    background-color: white;
    border-radius: 15px;
    padding: 12px 15px 0;
}
</style>
