<template>
    <div class="cardWrap">
        <div class="weekly-goals">
<!--            <h2>Weekly Goals <span>{{ goals.weekly_goals }}</span></h2>-->
            <h2>Weekly Goals <span> 25 </span></h2>
            <h3>Referrals Made <span class="text-success">{{ goals.referrals_made }}</span></h3>
            <h4>Weekly List</h4>
            <p v-for="item in goals.weekly_list">
                {{ item.day }}
                <span>{{ item.count > 0 ? item.count : '--' }}</span>
            </p>

            <h3 class="last">Remaining Goals <span class="text-danger">{{ goals.remaining_goals }}</span></h3>
        </div>
    </div>
</template>

<script>
export default {
    name: "WeeklyGoals",
    props: {
        goals: Object,
    },
}
</script>

<style scoped>

</style>
