<template>
    <div>
        <button type="button" class="btn btn-success" v-if="data.applies.length>0">
            Applied <span class="badge badge-light">{{data.applies.length}}</span>
        </button>
        <button type="button" class="btn btn-primary" v-else>
           Not Applied <span class="badge badge-light">0</span>
        </button>
    </div>
</template>

<script>
export default {
    name: "StatusCode",
    props:{
        data:{}
    }
}
</script>

<style scoped>

</style>
