import moment from "moment";
import {usePage} from "@inertiajs/inertia-vue3";
import {useToast} from "vue-toastification";

export default {
    namespaced: true,
    getters: {
        fromNow() {
            return (date) => moment(date).fromNow()
        },
        baseUrl() {
            let splitUrl = usePage().url.value.split('/')
            let url = []
            for (let i = 0; i < splitUrl.length; i++) {
                if (/\?/.test(splitUrl[i])) {
                    let splitQues = splitUrl[i].split('?')
                    if (splitQues[0])
                        url.push(splitQues[0])
                } else {
                    url.push(splitUrl[i])
                }
            }
            return url.join('/')
        },
        public_asset() {
            return (path) => route('home') + '/' + path
        },
        generateProfileLink() {
            return (user_id) => {
                const auth_id = usePage().props.value?.auth?.id
                return user_id === auth_id ? route('profile') : route('userProfile', user_id)
            }
        }
    },
    actions: {
        getVideoCover({}, {file_url, seekTo = 0.0}) {
            // console.log("getting video cover for file: ", file);
            return new Promise((resolve, reject) => {
                // load the file to a video player
                const videoPlayer = document.createElement('video');
                // videoPlayer.setAttribute('src', URL.createObjectURL(file));
                videoPlayer.setAttribute('src', file_url);
                videoPlayer.load();
                videoPlayer.addEventListener('error', (ex) => {
                    reject("error when loading video file", ex);
                });
                // load metadata of the video to get video duration and dimensions
                videoPlayer.addEventListener('loadedmetadata', () => {
                    // seek to user defined timestamp (in seconds) if possible
                    if (videoPlayer.duration < seekTo) {
                        reject("video is too short.");
                        return;
                    }
                    // delay seeking or else 'seeked' event won't fire on Safari
                    setTimeout(() => {
                        videoPlayer.currentTime = seekTo;
                    }, 200);
                    // extract video thumbnail once seeking is complete
                    videoPlayer.addEventListener('seeked', () => {
                        // console.log('video is now paused at %ss.', seekTo);
                        // define a canvas to have the same dimension as the video
                        const canvas = document.createElement("canvas");
                        canvas.width = videoPlayer.videoWidth;
                        canvas.height = videoPlayer.videoHeight;
                        // draw the video frame to canvas
                        const ctx = canvas.getContext("2d");
                        ctx.drawImage(videoPlayer, 0, 0, canvas.width, canvas.height);
                        // return the canvas image as a blob

                        ctx.canvas.toBlob(
                            blob => {
                                resolve(blob);
                            },
                            "image/jpeg",
                            0.75 /* quality */
                        );
                    });
                });
            });
        },
        showSuccessMessage() {
            (useToast()).clear();
            (useToast()).success(usePage().props.value?.flash?.success ?? 'Request submitted successfully!');
        },
        showErrorMessages() {
            return new Promise((resolve, reject) => {
                (useToast()).clear();
                const errors = usePage().props.value?.errors ?? {};
                for (const x in errors) {
                    (useToast()).error(errors[x]);
                    return reject()
                }
                if (usePage().props.value?.flash?.error) {
                    (useToast()).error(usePage().props.value.flash.error);
                    return reject()
                }
                resolve()
            })
        }
    }
}
