import {usePage} from "@inertiajs/inertia-vue3";
import _ from "lodash";

export default {
    namespaced: true,
    actions: {
        getReq({}, {url, only, params = {}}) {
            if (_.isArray(only))
                only = only.join(',')
            return new Promise((resolve, reject) => {
                axios.get(url, {
                    params: {
                        ...params
                    },
                    headers: {
                        'X-Inertia': true,
                        'X-Inertia-Partial-Component': usePage().component.value,
                        'X-Inertia-Partial-Data': only,
                        'X-Inertia-Version': usePage().version.value,
                    }
                }).then(res => {
                    resolve(res?.data?.props ?? res)
                }).catch(err => {
                    reject(err)
                })
            })

        }
    }
}
