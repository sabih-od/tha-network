<template>
    <div class="modal fade modal_create_avatar" ref="avatarModal" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Customize Avatar</h5>
                </div>
                <div class="modal-body">
                    <h5 class="modal-title" id="exampleModalLabel"><small>Send invitations to people to join your network</small></h5>
                    <form @submit.prevent="submit">
                        <div class="hello">
                            <h1>{{ msg }}</h1>
                            <div>
                                <button @click="randomize()">Generate Random</button>
                            </div>
                            <br>
                            <div>
                                <button @click="generateAvatar()">Generate Avatar</button>
                            </div>
                            <br>
                            <br>
                            <div class="container">
                                <div class="row">

                                    <div class="col-6 ">
                                        <figure class="sticky">

                                            <img :src="avatar.url" width="350" alt="avatar"/>
                                        </figure>
                                    </div>
                                    <div class="col-6">
                                        <div>
                                            <label for="gender">Gender </label>
                                            <select id="gender" v-model="choices.gender" @change="changeStyle()">
                                                <option v-for="gender in libMojiData.genders" :value="gender[1]">{{ gender[0] }}</option>
                                            </select>
                                        </div>
                                        <br>
                                        <div>
                                            <label for="styles">Styles </label>
                                            <select id="styles" v-model="choices.style" @change="changeStyle()">
                                                <option value="1">bitmoji</option>
                                                <!--          <option v-for="style in libMojiData.styles" :value="style[1]">{{ style[0] }}</option>-->
                                            </select>
                                        </div>
                                        <br>
                                        <div>
                                            <label for="poses">Poses </label>
                                            <select id="poses" v-model="choices.pose" @change="generateAvatar()">
                                                <option v-for="pose in libMojiData.poses" :value="pose">{{ pose }}</option>
                                            </select>
                                        </div>
                                        <br>
                                        <div>
                                            <label for="brand">Brands </label>
                                            <select id="brand" v-model="choices.brand" @change="generateOutfits()">
                                                <option v-for="(brand, key) in libMojiData.brands" :value="key">{{ brand.name }}</option>
                                            </select>
                                        </div>
                                        <br>
                                        <div>
                                            <label for="outfit">Outfit </label>
                                            <select id="outfit" v-model="choices.outfit" @change="generateAvatar()">
                                                <option v-for="(outfit, key) in libMojiData.outfits" :value="key">{{ outfit.outfit }}</option>
                                            </select>
                                        </div>
                                        <br>
                                        <div v-if="choices.pose == 'body'">
                                            <label for="rotation">Rotation </label>
                                            <select id="rotation" v-model="choices.rotation" @change="generateAvatar()">
                                                <option value="0">Straight</option>
                                                <option value="1">Right</option>
                                                <option value="7">Left</option>
                                            </select>
                                        </div>
                                        <br v-if="choices.pose == 'body'">
                                        <div v-for="(traits, key) in libMojiData.traits">
                                            <label :for="traits.key">{{ traits.key }}&nbsp;</label>
                                            <select :id="traits.key" v-model="choices.traits[traits.key]" :key="key" @change="generateAvatar()">
                                                <option v-for="(trait, key) in traits.options" :value="trait.value">{{traits.key}} {{ key+1 }}</option>
                                            </select>
                                            <br>
                                            <br>
                                        </div>
                                        <br>
<!--                                        <button @click="generateAvatar()">Generate Avatar</button>-->
                                        <button @click="profileImgUpload()">Confirm</button>
                                    </div>
                                </div>
                            </div>
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
import libmoji from "libmoji";
import {useForm} from "@inertiajs/inertia-vue3";

export default {
    name: 'CreateAvatar',
    data() {
        return {
            form: useForm({
                url: ''
            }),
            choices: {
                gender: 1,
                genderStr: '',
                style: 1,
                styleStr: '',
                pose: 'body',
                brand: 0,
                traits: {},
                outfit: 0,
                rotation: 0,
            },
            avatar: {
                url: '',
                gender: '',
                style: '',
                traits: '',
                outfit: '',
            },
            libMojiData: {
                genders: {},
                styles: {},
                poses: {},
                traits: {},
                outfits: {},
                brands: {},
            }
        }
    },
    props: {
        msg: String,
    },
    mounted() {
        this.setLibmojiData()
        this.randomize();
    },
    methods: {
        setLibmojiData() {
            this.libMojiData.genders = libmoji.genders;
            this.libMojiData.styles = libmoji.styles;
            this.libMojiData.poses = libmoji.poses;
            this.generateTraits()
            this.libMojiData.brands = libmoji.getBrands(this.choices.genderStr);
            this.libMojiData.outfits = libmoji.getOutfits(this.libMojiData.brands[this.choices.brand]);
        },
        generateTraits() {
            this.choices.genderStr = this.choices.gender == 1 ? 'male' : 'female';
            if (this.choices.style == 1) {
                this.choices.styleStr = 'bitstrips'
            } else if (this.choices.style == 4) {
                this.choices.styleStr = 'bitmoji'
            } else if (this.choices.style == 5) {
                this.choices.styleStr = 'cm'
            }
            this.libMojiData.traits = libmoji.getTraits(this.choices.genderStr, this.choices.styleStr);
        },
        generateOutfits() {
            this.libMojiData.outfits = libmoji.getOutfits(this.libMojiData.brands[this.choices.brand]);
            this.choices.outfit = 0;
            this.generateAvatar()
        },
        changeStyle() {
            this.generateTraits()
        },
        generateAvatar() {
            let choices = this.choices;

            let trait = [];
            const traitKeys = Object.keys(choices.traits);
            const traitValues = Object.values(choices.traits);
            for (let i = 0; i < traitValues.length; i++) {
                trait.push([
                    traitKeys[i], traitValues[i]
                ])
            }

            let outfit = this.libMojiData.outfits[this.choices.outfit].id;

            this.avatar.url = libmoji.buildPreviewUrl(
                choices.pose,
                3,
                choices.gender,
                choices.style,
                choices.rotation,
                trait,
                outfit
            );
        },
        randomize() {
            let gender = libmoji.genders[libmoji.randInt(2)];
            let style = libmoji.styles[libmoji.randInt(3)];
            let traits = libmoji.randTraits(libmoji.getTraits(gender[0], style[0]));
            let outfit = libmoji.randOutfit(
                libmoji.getOutfits(libmoji.randBrand(libmoji.getBrands(gender[0])))
            );

            this.avatar.gender = gender;
            this.avatar.style = style;
            this.avatar.traits = traits;
            this.avatar.outfit = outfit;

            this.avatar.url = libmoji.buildPreviewUrl(
                "fashion",
                3,
                gender[1],
                style[1],
                0,
                traits,
                outfit
            );
        },
        profileImgUpload() {
            console.log(this.avatar.url);
            this.form.url = this.avatar.url;

            this.form.post(this.$route('profileImgUpload'), {
                replace: true,
                onSuccess: () => {
                    this.$store.dispatch('Utils/showSuccessMessage')
                    $('.modal_create_avatar').modal('hide');
                    $('.modal_create_avatar').find('form').trigger('reset');
                },
                onFinish: () => {
                    this.$store.dispatch('Utils/showErrorMessages').then(res => {
                        this.isEdit = false
                    })
                }
            })
        }
    }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
h3 {
    margin: 40px 0 0;
}

ul {
    list-style-type: none;
    padding: 0;
}

li {
    display: inline-block;
    margin: 0 10px;
}

a {
    color: #42b983;
}
.container{
    width: 1280px;
    margin: 0 auto;
}
.row {
    display: flex;
}

.col-6 {
    flex: 0 1 50%;
    width: 50%
}

.sticky {
    position: sticky;
    top: -1px;
}
</style>
