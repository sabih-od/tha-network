import {Inertia} from "@inertiajs/inertia";
import _ from "lodash";

export default {
    namespaced: true,
    state() {
        return {
            loading: false,
            isLoadMore: false,
            posts: [],
            next_page_url: null,
            initialUrl: null,
            queue_data: {
                type: 'post_load',
                url: null
            }
        }
    },
    getters: {
        getSinglePost(state) {
            return (id) => {
                return _.find(state.posts, {id})
            }
        },
        getSharedPost(state) {
            return (post_id) => {
                return _.find(state.posts, {post_id})?.shared_post ?? null
            }
        }
    },
    mutations: {
        setQueueData(state, url) {
            state.queue_data = {
                ...state.queue_data,
                url
            }
        },
        setLoading(state, payload) {
            state.loading = payload
        },
        setIsLoadMore(state, payload) {
            state.isLoadMore = payload
        },
        setNextPageUrl(state, payload) {
            state.next_page_url = payload
        },
        setInitialUrl(state, payload) {
            state.initialUrl = payload
        },
        setPosts(state, payload) {
            if (state.isLoadMore)
                state.posts = [
                    ...state.posts,
                    ...payload
                ]
            else
                state.posts = payload
        },
        setSinglePost(state, {id, postData}) {
            const post = _.find(state.posts, {id})
            _.set(state.posts, _.findIndex(state.posts, {id}), {
                ...post,
                ...postData
            })
        },
        setFollowStatus(state, {user_id, path, value}) {
            _.each(state.posts, item => {
                if (item?.user_id === user_id && _.has(item, path)) {
                    _.set(item, path, value)
                }
                if (item?.shared_post?.user_id === user_id && _.has(item, 'shared_post.' + path)) {
                    _.set(item, 'shared_post.' + path, value)
                }
            })
        }
    },
    actions: {
        loadPosts({state, commit, dispatch, rootState}, url) {
            if (state.loading) return

            if (url) {
                commit('setQueueData', url)

                console.log("in load posts", rootState.LoadingQueue.loading, rootState.LoadingQueue.runningQueue)
                if (rootState.LoadingQueue.loading)
                    return;

                // queue loading emit
                dispatch('LoadingQueue/initQueue', state.queue_data, {root: true})

                Inertia.get(url, {}, {
                    replace: true,
                    preserveScroll: true,
                    preserveState: true,
                    only: ['posts'],
                    onStart: () => {
                        commit('setLoading', true)
                    },
                    onSuccess: visit => {
                        commit('setNextPageUrl', visit.props?.posts?.next_page_url ?? null)
                        commit('setPosts', visit.props?.posts?.data ?? [])
                        dispatch('LoadingQueue/reInit', null, {root: true})
                    },
                    onFinish: () => {
                        commit('setLoading', false)
                        window.history.replaceState({}, '', state.initialUrl)
                    }
                })
            }
        }
    }
}
