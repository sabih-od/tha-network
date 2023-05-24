import {createStore} from 'vuex'
import Profile from './Profile'
import Utils from './Utils'
import SharePostModal from './SharePostModal'
import HttpUtils from "./HttpUtils";
import Chat from "./Chat";
import Channel from "./Channel";
import Notification from "./Notification";
import Misc from "./Misc";
import AuthUser from "./AuthUser";

export default createStore({
    modules: {
        Profile,
        Utils,
        SharePostModal,
        HttpUtils,
        Chat,
        Channel,
        Notification,
        Misc,
        AuthUser,
    }
})
