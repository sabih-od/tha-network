import {createStore} from 'vuex'
import Profile from './Profile'
import Post from './Post'
import Utils from './Utils'
import SharePostModal from './SharePostModal'
import LoadingQueue from "./LoadingQueue";

export default createStore({
    modules: {
        Profile,
        Post,
        Utils,
        SharePostModal,
        LoadingQueue,
    }
})
