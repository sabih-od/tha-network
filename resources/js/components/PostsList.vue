<template>
    <div>
        <div class="cardWrap" v-if="people_in_my_network_flag">
            <div class="df aic jcsb mb-3">
                <h2 class="m-0">People In My Network</h2>
                <a href="#" @click.prevent="peopleInMyNetworkOff" class="viewBtn">Back to feed</a>
            </div>
            <div class="userList" v-for="user in peoples">
                <div class="userInfo">
                    <Link :href="$route('userProfile', user.id)"><img :src="user.profile_img ? user.profile_img : asset('images/char-usr.png')" class="rounded-circle" alt=""></Link>
                    <h3>
                        <Link :href="$route('userProfile', user.id)">
                            <!--                                <strong>{{user.profile ? user.profile.first_name +' '+ user.profile.last_name : ''}}</strong>-->
                            <strong>{{ user.username }}</strong>
                        </Link>
                        <a href="#">Connect</a>
                    </h3>
                </div>
                <FollowUserButton v-if="!isMe(user.id)" :user_id="user.id" :is_followed_by_auth="user.is_followed_by_auth" :is_followed="user.is_followed" :request_sent="user.request_sent" :request_received="user.request_received" @update_is_followed="user.is_followed = !user.is_followed"></FollowUserButton>
                <!--            <a href="#" class="nav-icons"><i class="fal fa-comments"></i></a>-->
            </div>

            <div style="text-align: center!important;" v-if="peoples.length == 0 && search == ''">
                <h6>{{ peoples_wait_text }}</h6>
            </div>
        </div>
        <div id="blocked" class="cardWrap" v-if="blocked_users_flag">
            <div class="df aic jcsb mb-3">
                <h2 class="m-0">Blocked Users</h2>
                <a href="#" @click.prevent="blockedUsersOff" class="viewBtn">Back to feed</a>
            </div>
            <div class="userList" v-for="user in blocked_users">
                <div class="userInfo">
                    <Link :href="$route('userProfile', user.id)"><img :src="user.profile_img ? user.profile_img : asset('images/char-usr.png')" class="rounded-circle" alt=""></Link>
                    <h3>
                        <Link :href="$route('userProfile', user.id)">
                            <!--                                <strong>{{user.profile ? user.profile.first_name +' '+ user.profile.last_name : ''}}</strong>-->
                            <strong>{{ user.username }}</strong>
                        </Link>
                        <a href="#">Connect</a>
                    </h3>
                </div>
                <FollowUserButton v-if="!isMe(user.id)" :user_id="user.id" :is_followed_by_auth="user.is_followed_by_auth" :is_followed="user.is_followed" :request_sent="user.request_sent" :request_received="user.request_received" @update_is_followed="user.is_followed = !user.is_followed"></FollowUserButton>
                <!--            <a href="#" class="nav-icons"><i class="fal fa-comments"></i></a>-->
            </div>

            <div style="text-align: center!important;" v-if="blocked_users.length == 0 && search == ''">
                <h6>{{ blocked_users_wait_text }}</h6>
            </div>
        </div>
        <div class="col-md-12" v-if="my_friends_flag">
            <div class="cardWrap">
                <div class="df aic jcsb mb-3">
                    <h2 class="m-0">My Friends</h2>
                    <a href="#" @click.prevent="myFriendsOff" class="viewBtn">Back to feed</a>
                </div>
                <div class="userList" v-for="user in my_friends">
                    <div class="userInfo">
                        <Link :href="$route('userProfile', user.id)"><img :src="user.profile_img ? user.profile_img : asset('images/char-usr.png')" class="rounded-circle" alt=""></Link>
                        <h3>
                            <Link :href="$route('userProfile', user.id)">
<!--                                <strong>{{user.profile ? user.profile.first_name +' '+ user.profile.last_name : ''}}</strong>-->
                                <strong>{{ user.username }}</strong>
                            </Link>
                            <a href="#">Connect</a>
                        </h3>
                    </div>
                    <FollowUserButton v-if="!isMe(user.id)" :user_id="user.id" :is_followed_by_auth="user.is_followed_by_auth" :is_followed="user.is_followed" :request_sent="user.request_sent" :request_received="user.request_received" @update_is_followed="user.is_followed = !user.is_followed"></FollowUserButton>
                    <!--            <a href="#" class="nav-icons"><i class="fal fa-comments"></i></a>-->
                </div>

                <div style="text-align: center!important;" v-if="my_friends.length == 0 && search == ''">
                    <h6>{{ my_friends_wait_text }}</h6>
                </div>
            </div>
        </div>
        <div class="col-md-12" v-if="all_users_flag">
            <div class="cardWrap">
                <div class="df aic jcsb mb-3">
                    <h2 class="m-0">All Users</h2>
                    <a href="#" @click.prevent="allUserssOff" class="viewBtn">Back to feed</a>
                </div>
                <div class="userList" v-for="user in all_users">
                    <div class="userInfo">
                        <Link :href="$route('userProfile', user.id)"><img :src="user.profile_img ? user.profile_img : asset('images/char-usr.png')" class="rounded-circle" alt=""></Link>
                        <h3>
                            <Link :href="$route('userProfile', user.id)">
<!--                                <strong>{{user.profile ? user.profile.first_name +' '+ user.profile.last_name : ''}}</strong>-->
                                <strong>{{ user.username }}</strong>
                            </Link>
                            <a href="#">Connect</a>
                        </h3>
                    </div>
                    <FollowUserButton v-if="!isMe(user.id)" :user_id="user.id" :is_followed_by_auth="user.is_followed_by_auth" :is_followed="user.is_followed" :request_sent="user.request_sent" :request_received="user.request_received" @update_is_followed="user.is_followed = !user.is_followed"></FollowUserButton>
                    <!--            <a href="#" class="nav-icons"><i class="fal fa-comments"></i></a>-->
                </div>

                <div style="text-align: center!important;" v-if="all_users.length == 0 && search == ''">
                    <h6>{{ all_users_wait_text }}</h6>
                </div>
            </div>
        </div>
        <PostListItem
            v-for="(post, index) in block_filtered_posts"
            v-if="!people_in_my_network_flag && !blocked_users_flag && !my_friends_flag && !all_users_flag"
            :post="post"
            :key="post.id"
            :id="'ref_post_list_item' + index"
        />
        <h3 v-if="loading" class="text-secondary mb-5 text-center">Loading...</h3>
        <h3 v-if="!loading && posts.length < 1" class="text-secondary my-5 text-center">No posts at the moment!</h3>
        <teleport to="body">
            <SharePostModal/>
        </teleport>
    </div>
</template>

<script>
import utils from "../mixins/utils";
import PostListItem from "./PostListItem";
import SharePostModal from "./SharePostModal";
import {Inertia} from "@inertiajs/inertia";
import _ from "lodash";
import {usePage} from "@inertiajs/inertia-vue3";

export default {
    name: "PostsList",
    mixins: [utils],
    components: {
        PostListItem,
        SharePostModal
    },
    computed: {
        isMe() {
            return (user_id) => {
                if (user_id && usePage().props.value?.auth?.id)
                    return user_id === usePage().props.value?.auth?.id
                return false
            }
        },
        block_filtered_posts: function () {
            return this.posts.filter(i => (!i.new_block_property));
        }
    },
    data() {
        return {
            loading: false,
            next_page_url: null,
            posts: [],
            people_in_my_network_flag: this.$store.getters['Misc/getPeopleInMyNetworkFlag'],
            blocked_users_flag: this.$store.getters['Misc/getBlockedUsersFlag'],
            my_friends_flag: this.$store.getters['Misc/getMyFriendsFlag'],
            all_users_flag: this.$store.getters['Misc/getAllUsersFlag'],
            all_users_wait_text: 'No Customers Found.',
            peoples_wait_text: 'There is no user in my network.',
            blocked_users_wait_text: 'There are no blocked users.',
            my_friends_wait_text: 'There are no friends in my list.',
            search: '',
            peoples: [],
            blocked_users: [],
            my_friends: [],
            all_users: [],
            debounce: null,
            all: true
        }
    },
    mounted() {
        this.loadPosts()
        this.initateNetworkMemberSearch()
        this.initateBlockedUsersSearch()
        this.initateMyFriendsSearch()
        this.initateAllUsersSearch()
        window.addEventListener('scroll', this.listener);
        this.$emitter.on('post-created', this.onPostCreated)
        this.$emitter.on('post-shared', this.onPostShared)
        this.$emitter.on('comment-created', this.onCommentCreated)
        this.$emitter.on('post-like-toggle', this.onLikeToggle)
        this.$emitter.on('post-deleted', this.loadPosts)
        this.$emitter.on('my-post-loading', (val) => {
            this.loadPosts(null, val, null, true)
        })
        this.$emitter.on('setPeopleInMyNetworkFlagOff', (val) => {
            this.$store.commit('Misc/setPeopleInMyNetworkFlag', false);
            _t.people_in_my_network_flag = this.$store.getters['Misc/getPeopleInMyNetworkFlag'];
        })
        this.$emitter.on('setBlockedUsersFlagOff', (val) => {
            this.$store.commit('Misc/setBlockedUsersFlag', false);
            _t.blocked_users_flag = this.$store.getters['Misc/getBlockedUsersFlag'];
        })

        let _t = this;
        this.$emitter.on('fetch_post_on_top', function(post_id) {
            _t.loadPosts(null, true, post_id);
        })
        this.$emitter.on('people_in_my_network_on', function() {
            _t.people_in_my_network_flag = true;
            _t.initateNetworkMemberSearch()
        })
        this.$emitter.on('people_in_my_network_off', function() {
            _t.people_in_my_network_flag = false;
        })
        this.$emitter.on('blocked_users_on', function() {
            _t.blocked_users_flag = true;
            _t.initateBlockedUsersSearch()
        })
        this.$emitter.on('blocked_users_off', function() {
            _t.blocked_users_flag = false;
        })
        this.$emitter.on('my_friends_on', function() {
            _t.my_friends_flag = true;
            _t.initateMyFriendsSearch()
        })
        this.$emitter.on('my_friends_off', function() {
            _t.my_friends_flag = false;
        })
        this.$emitter.on('all_users_off', function() {
            _t.all_users_flag = false;
        })
    },
    unmounted() {
        window.removeEventListener('scroll', this.listener)
        this.$emitter.off('post-created')
        this.$emitter.off('post-shared')
        this.$emitter.off('comment-created')
        this.$emitter.off('post-like-toggle')
        this.$emitter.off('post-deleted')
        this.$emitter.off('my-post-loading')
    },
    methods: {
        loadPosts(url = null, is_my_posts = false, post_id = null, changeText = false) {
            // alert('is_my_posts ' + this.$store.getters['Misc/getIsMyPosts']);
            if (this.loading) return;

            if(changeText) {
                this.$emitter.emit('change_my_posts_button_text', is_my_posts);
            }

            let isLoadMore = !!(url)
            url = url ?? this.$store.getters['Utils/baseUrl']

            this.loading = true
            if (!isLoadMore)
                this.posts = []

            let imp = this.$store.getters['Misc/getIsMyPosts'];
            this.$store.dispatch('HttpUtils/getReq', {
                url: url,
                only: ['posts'],
                params: {
                    is_my_posts: imp ? 1 : 0,
                    post_id: post_id
                }
            }).then(res => {
                this.next_page_url = res?.posts?.next_page_url ?? null
                if (isLoadMore)
                    this.posts = [
                        ...this.posts,
                        ...(res?.posts?.data ?? [])
                    ]
                else
                    this.posts = res?.posts?.data ?? []
            }).finally(() => {
                this.loading = false
            })
        },
        listener() {
            const appElHeight = document.getElementById('app')?.offsetHeight ?? document.documentElement.offsetHeight
            let bottomOfWindow = document.documentElement.scrollTop + window.innerHeight + 300 >= appElHeight;
            if (bottomOfWindow && this.next_page_url) {
                this.loadPosts(this.next_page_url)
            }
        },
        onPostCreated() {
            this.loadPosts()
        },
        onPostShared() {
            window.scrollTo(0, 0);
            this.loadPosts()
        },
        setPostData(post_id, post_data = {}) {
            const post = _.find(this.posts, {id: post_id})
            if (post)
                _.set(this.posts, _.findIndex(this.posts, {id: post_id}), {
                    ...post,
                    ...post_data
                })
        },
        onCommentCreated(post_id) {
            const post = _.find(this.posts, {id: post_id})
            this.setPostData(post_id, {
                comments_count: post.comments_count + 1
            })
        },
        onLikeToggle({post_id, post_data}) {
            this.setPostData(post_id, post_data)
        },
        peopleInMyNetworkOff() {
            this.$store.commit('Misc/setPeopleInMyNetworkFlag', false);
            this.$emitter.emit('people_in_my_network_off');
        },
        blockedUsersOff() {
            this.$store.commit('Misc/setBlockedUsersFlag', false);
            this.$emitter.emit('blocked_users_off');
        },
        myFriendsOff() {
            this.$store.commit('Misc/setMyFriendsFlag', false);
            this.$emitter.emit('my_friends_off');
        },
        allUserssOff() {
            this.$store.commit('Misc/setAllUsersFlag', false);
            this.$emitter.emit('all_users_off');
        },
        initateNetworkMemberSearch() {
            clearTimeout(this.debounce);
            this.peoples = []
            this.peoples_wait_text = 'Please Wait.'
            this.debounce = setTimeout(() => {
                this.$store.dispatch('HttpUtils/getReq', {
                    url: this.$store.getters['Utils/baseUrl'],
                    only: ['network_members'],
                    params: {
                        search: this.search,
                        user_id: this.user_id,
                        all: this.all
                    }
                }).then(res => {
                    this.peoples = res?.network_members?.data.filter(element => element.has_blocked == false) ?? []
                }).finally(() => {
                    // this.loading = false
                    this.peoples_wait_text = (this.peoples.length == 0) ? 'There is no user in my network.' : 'Please Wait';
                })
            }, 600);
        },
        initateBlockedUsersSearch() {
            // clearTimeout(this.debounce);
            this.blocked_users = []
            this.blocked_users_wait_text = 'Please Wait.'
            this.debounce = setTimeout(() => {
                this.$store.dispatch('HttpUtils/getReq', {
                    url: this.$store.getters['Utils/baseUrl'],
                    only: ['blocked_users'],
                    params: {
                        search: this.search,
                        user_id: this.user_id,
                        all: this.all
                    }
                }).then(res => {
                    this.blocked_users = res?.blocked_users?.filter(element => element.is_blocked == true) ?? []
                }).finally(() => {
                    // this.loading = false
                    this.blocked_users_wait_text = (this.blocked_users.length == 0) ? 'There are no blocked users.' : 'Please Wait';
                })
            }, 600);
        },
        initateMyFriendsSearch() {
            // clearTimeout(this.debounce);
            this.my_friends = []
            this.my_friends_wait_text = 'Please Wait.'
            this.debounce = setTimeout(() => {
                this.$store.dispatch('HttpUtils/getReq', {
                    url: this.$store.getters['Utils/baseUrl'],
                    only: ['friends'],
                    params: {
                        search: this.search,
                        user_id: this.user_id,
                        all: this.all
                    }
                }).then(res => {
                    this.my_friends = res?.friends?.filter(element => element.is_followed == true && element.is_followed_by_auth == true) ?? []
                }).finally(() => {
                    // this.loading = false
                    this.my_friends_wait_text = (this.my_friends.length == 0) ? 'There are no friends in my list.' : 'Please Wait';
                })
            }, 600);
        },
        initateAllUsersSearch() {
            // clearTimeout(this.debounce);
            this.all_users = []
            this.all_users_wait_text = 'Please Wait.'
            this.debounce = setTimeout(() => {
                this.$store.dispatch('HttpUtils/getReq', {
                    url: this.$store.getters['Utils/baseUrl'],
                    only: ['all_users'],
                    params: {

                    }
                }).then(res => {
                    this.all_users = res?.all_users ?? []
                }).finally(() => {
                    // this.loading = false
                    this.all_users_wait_text = (this.all_users.length == 0) ? 'No Customers Found.' : 'Please Wait';
                })
            }, 600);
        },
    }
}
</script>

<style scoped>

</style>
