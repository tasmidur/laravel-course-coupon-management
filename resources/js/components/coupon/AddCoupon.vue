<template>
    <div>
        <h3 class="text-center">Add Coupon</h3>
        <div class="row">
            <div class="col-md-6" style="margin: 0 auto">
                <form @submit.prevent="addCoupon">
                    <div class="form-group">
                        <label>CouponType*</label>
                        <select class="form-control" v-model="payload.coupon_type" required>
                            <option value="">Select</option>
                            <option value="FIXED_PRICE">Fixed Price</option>
                            <option value="DISCOUNT_PRICE">Discount Price</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>CouponPrice*</label>
                        <input type="number" class="form-control" v-model="payload.price"
                               placeholder="Coupon Price" required>
                    </div>
                    <div class="form-group">
                        <label>ExpiredAt</label>
                        <input type="datetime-local" class="form-control" v-model="payload.expired_at">
                    </div>
                    <button type="submit" class="btn btn-primary">Add Post</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import {BASE_URL, HTTP_CREATED, HTTP_OK, HTTP_UNPROCESSABLE_ENTITY} from "../../common";
import moment from "moment";

export default {
    data() {
        return {
            payload: {}
        }
    },
    methods: {
        addCoupon() {
            this.axios
                .post(`${BASE_URL}/coupons`, this.payload)
                .then((response) => {
                    const responseData = response.data;
                    this.$toast.success(responseData.message);
                }).catch((errors) => {
                const errorMessage = errors.response;
                if (errorMessage.status === HTTP_UNPROCESSABLE_ENTITY && errorMessage.data.error) {
                    errorMessage.data.error.map((element) => {
                        this.$toast.error(element);
                    })
                }
            }).finally(() => {
                this.$router.push({
                    name: "home"
                });
            })

        }
    }
}
</script>
