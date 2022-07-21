<template>
    <router-link :to="{name: path, params: params}" :class="classes">
        {{name}}
    </router-link>
</template>

<script>
export default {
    props: {
        params: {},
        path: {},
        name:"",
        click: {
            type: Function,
            default: () => {
            }
        },
        classes: {
            type: Object,
            default: () => ({
                'btn': true,
                'btn-primary': true,
                'btn-sm': true,
            }),
        },
    }
}
</script>

<style scoped>

</style>
