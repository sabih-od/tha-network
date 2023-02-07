import _ from "lodash";

export default {
    data() {
        return {
            _q_loading: false,
            _running_queue: null,
            _queues: []
        }
    },
    computed: {
        isDataInQueue() {
            return (data) => {
                return !_.isEmpty(_.find(this._queues, val => _.isEqual(val, data)))
            }
        }
    },
    mounted() {
        this.$emitter.on('new_queue_added', (data) => {
            this._initQueue(data)
        })
        this.$emitter.on('next_queue_run', () => {
            this._reInit()
        })
    },
    unmounted() {
        this.$emitter.off('new_queue_added')
        this.$emitter.off('next_queue_run')
    },
    methods: {
        _q_setLoading(payload) {
            this._q_loading = payload
        },
        _q_setRunningQueue(payload) {
            this._running_queue = payload
        },
        _q_addQueue(payload) {
            this._queues.push(payload)
        },
        _q_removeQueue(payload) {
            _.remove(this._queues, function (val) {
                return _.isEqual(val, payload);
            })
        },
        _reInit() {
            if (this._running_queue) {
                this._q_removeQueue(this._running_queue)
                this._q_setRunningQueue(null)
            }
            this._q_setLoading(false)
            const item = _.first(this._queues)
            if (item) {
                this._initQueue(item)
            }
        },
        _initQueue(data) {
            if (!data) return;
            if (this._q_loading) {
                if (!this.isDataInQueue(data))
                    this._q_addQueue(data)
                return
            }
            this._q_setLoading(true)
            this._q_setRunningQueue(data)
            this.$emitter.emit('queue_running', data)
        }
    }
}
