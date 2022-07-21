<template>
    <div class="btn-group float-right" role="group" aria-label="Basic example">
        <button type="button"
                class="btn btn-primary"
                @click="click({...data,apply:true})"
                data-toggle="modal"
                data-target="#coupon-where-apply-modal"
        >Apply</button>
        <button type="button" class="btn btn-info" @click="click({...data,edit:true})">Edit</button>
        <button type="button" class="btn btn-danger" @click="click({...data,delete:true})">Delete</button>
    </div>
</template>

<script>
export default {
    props: {
        data: {},
        name: "",
        action: {
            apply: "apply",
            edit: "edit",
            delete: "delete"
        },
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
