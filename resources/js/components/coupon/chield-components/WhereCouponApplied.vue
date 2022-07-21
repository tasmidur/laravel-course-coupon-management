<template>
    <div>
        <ul class="list-group">
            <li class="list-group-item" v-for="coupon in coupons">
                <p>
                    CouponCode: {{ coupon.coupon_code }}
                </p>
                <p>
                    ExpiredAt: {{ coupon.expired_at }}
                </p>
                <h4>Applied:</h4>
                <ul class="list-group">
                    <li class="list-group-item" v-for="apply in coupon.applies">
                        <p>
                            CourseTitle: {{ apply.title }}
                        </p>
                        <p>
                            CoursePrice: {{ apply.course_fee }}
                        </p>
                        <p v-if="coupon.coupon_type === 'DISCOUNT_PRICE'">
                            CouponPrice: {{ coupon.price }}% <strong>DISCOUNT</strong>
                        </p>
                        <p v-else>
                            CouponPrice: {{ coupon.price }} TK
                        </p>
                        <p>
                            CouponNetPrice: <span class="badge badge-pill badge-primary">{{ netPriceCalculate(coupon, apply.course_fee) }}/ <del>{{ apply.course_fee }}</del> TK</span>
                        </p>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</template>

<script>
import CouponList from "./CouponList";
import {BASE_URL} from "../../../common";

export default {
    data() {
        return {
            coupons: [],
            code: this.$route.params.code
        }
    },
    methods: {
        getCouponAppliedData() {
            axios.get(`${BASE_URL}/where-apply-code/${this.code}`)
                .then(response => {
                    this.coupons = response.data.data;
                })
                .catch(errors => {
                    console.log(errors);
                })
        },
        netPriceCalculate(coupon, courseFee) {
            if (coupon.coupon_type === 'FIXED_PRICE') {
                return parseFloat(courseFee) - parseFloat(coupon.price)
            } else if (coupon.coupon_type === 'DISCOUNT_PRICE') {
                const parseCourseFee = parseFloat(courseFee);
                const parseCouponPrice = parseFloat(coupon.price);
                const netFee = parseCourseFee - (parseCourseFee * parseCouponPrice / 100);
                console.log(netFee);
                return netFee
            }
        }
    },
    created() {
        this.getCouponAppliedData();
        console.log(this.coupons)
    }
}

</script>

<style scoped>

</style>
