<template>
    <div>
        <ul>
            <li v-for="coupon in data.coupons">
                <p>
                    CouponCode: {{ coupon.coupon_code }}
                </p>
                <p>
                    ExpiredAt: {{ coupon.expired_at }}
                </p>
                <p v-if="coupon.coupon_type === 'DISCOUNT_PRICE'">
                    CouponPrice: {{ coupon.price }}% <strong>DISCOUNT</strong>
                </p>
                <p v-else>
                    CouponPrice: {{ coupon.price }} TK
                </p>
                <p>
                    <strong>
                        Net Course Fee: {{ netPriceCalculate(coupon, data.course_fee) }}
                    </strong>
                </p>
            </li>
        </ul>
    </div>
</template>

<script>
export default {
    name:"CouponList",
    props: {
        data: {}
    },
    methods: {
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
    }
}
</script>

<style scoped>

</style>
