<template>
    <div>
        <h3 class="text-center">Edit Post</h3>
        <div class="row">
            <div class="col-md-6" style="margin: 0 auto">
                <form @submit.prevent="updatePost">
                    <div class="form-group">
                        <label>CouponType*</label>
                        <select class="form-control" v-model="formData.coupon_type" required>
                            <option value="">Select</option>
                            <option value="FIXED_PRICE">Fixed Price</option>
                            <option value="DISCOUNT_PRICE">Discount Price</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>CouponPrice*</label>
                        <input type="number" class="form-control" v-model="formData.price"
                               placeholder="Coupon Price" required>
                    </div>
                    <div class="form-group">
                        <label>ExpiredAt</label>
                        <input type="datetime-local" class="form-control" v-model="formData.expired_at">
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import {BASE_URL, HTTP_UNPROCESSABLE_ENTITY} from "../../common";
import moment from "moment";

export default {
    data() {
        return {
            formData: {}
        }
    },
    created() {
        this.axios
            .get(`${BASE_URL}/coupons/${this.$route.params.id}`)
            .then((response) => {
                this.formData = response.data.data;
            });
    },
    methods: {
        updatePost() {
            this.axios
                .put(`${BASE_URL}/coupons/${this.$route.params.id}`, this.formData)
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
