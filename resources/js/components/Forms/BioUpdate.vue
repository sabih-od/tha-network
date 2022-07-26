<template>
    <form class="edit-card" @submit.prevent="submit">
        <a v-if="!isEdit" href="#" @click.prevent="isEdit = true" class="editProBtn">Edit</a>
        <template v-if="isEdit">
            <div class="d-flex">
                <a href="#" @click.prevent="isEdit = false" class="editProBtn w-auto">Cancel</a>
                <a href="#" @click.prevent="" class="editProBtn ml-2">Save</a>
            </div>
        </template>
        <div class="cardWrap">
            <div class="form-group mb-0">
                <label for="bio">bio</label>
                <textarea name="bio" id="bio" cols="10" class="form-control"
                          placeholder="Enter Bio"
                          :readonly="!isEdit"
                          rows="3"></textarea>
            </div>
        </div>
    </form>
</template>

<script>
export default {
    name: "BioUpdate",
    data() {
      return {
          isEdit: false
      }
    },
    methods: {
        submit() {

        }
    }
}
</script>

<style scoped>

</style>
