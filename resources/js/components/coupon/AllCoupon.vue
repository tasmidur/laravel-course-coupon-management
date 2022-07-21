<template>
    <div>
        <data-table
            :data="data"
            :columns="columns"
            @on-table-props-changed="reloadTable">
        </data-table>
    </div>
</template>
<script>
import {BASE_URL} from "../../common";
import ActionButton from "./chield-components/ActionButtonGroup";
import StatusCode from './chield-components/StatusCode';

export default {
    data() {
        return {
            modalData: {},
            data: {},
            url: `${BASE_URL}/coupons`,
            tableProps: {
                search: '',
                length: 10,
                column: 'id',
                dir: 'asc'
            },
            columns: [
                {
                    label: 'ID',
                    name: 'id',
                    orderable: true,
                },
                {
                    label: 'CouponCode',
                    name: 'coupon_code',
                    orderable: false,
                },
                {
                    label: 'CouponType',
                    name: 'coupon_type',
                    orderable: false,
                },
                {
                    label: 'CouponPrice',
                    name: 'price',
                    orderable: false,
                },
                {
                    label: 'ExpiredAt',
                    name: 'expired_at',
                    orderable: false,
                },
                {
                    label: 'Status',
                    name: 'status',
                    orderable: false,
                    component: StatusCode,
                },
                {
                    label: '',
                    name: 'Delete',
                    orderable: false,
                    event: "click",
                    handler: this.actionButton,
                    component: ActionButton,
                }
            ]
        }
    },
    methods: {
        getData(url = this.url, options = this.tableProps) {
            axios.get(url, {
                params: options
            })
                .then(response => {
                    this.data = response.data;
                })
                .catch(errors => {
                    console.log(errors);
                })
        },
        reloadTable(tableProps) {
            this.getData(this.url, tableProps);
        },
        deleteCoupon(deleteData) {
            this.axios
                .delete(`${BASE_URL}/coupons/${deleteData.id}`)
                .then(response => {
                    this.getData(this.url);
                });
        },
        actionButton(rowData) {
            if (rowData.delete) {
                this.deleteCoupon(rowData)
            } else if (rowData.edit) {
                this.$router.push(
                    {
                        name: 'edit',
                        params: {
                            id: rowData.id
                        }
                    });
            } else if (rowData.apply) {
                this.$router.push({
                    name: "where-coupon-applied",
                    params: {
                        code: rowData.coupon_code
                    }
                })
            }
        }
    },
    created() {
        this.getData(this.url);
    }
}
</script>
