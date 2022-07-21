<template>
    <div>
        <h3 class="text-center">Apply Coupon</h3>
        <div class="row">
            <div class="col-md-8" style="margin: 0 auto">
                <form @submit.prevent="applyCoupon">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="inputState">Category</label>
                            <select id="inputState" class="form-control" v-model="courseCategoryId" required>
                                <option v-for="category in courseCategoryList" v-bind:key="category.id"
                                        v-bind:value="category.id"> {{ category.title }}
                                </option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="inputState">Courses</label>
                            <select id="inputState" class="form-control" v-model="courseId">
                                <option v-for="course in courseList" v-bind:key="course.id"
                                        v-bind:value="course.id"> {{ course.title }} -> CourseFee: {{
                                        course.course_fee
                                    }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <div class="form-group col-md-">
                            <label for="inputState">Coupon Code</label>
                            <select id="inputState" class="form-control" required v-model="couponCode">
                                <option v-for="coupon in couponList" v-bind:key="coupon.coupon_code"
                                        v-bind:value="coupon.coupon_code">
                                    {{ coupon.coupon_code }} -> price: {{ coupon.price }}
                                    <span v-if="coupon.coupon_type === 'DISCOUNT_PRICE'">
                                        %
                                    </span>
                                    <span v-else>
                                        TK
                                    </span>
                                    -> expireAt:
                                    {{ coupon.expired_at }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Apply</button>
                </form>
            </div>
        </div>
        <h3 class="text-center">Applied Coupon List</h3>
        <hr>
        <data-table
            :data="data"
            :columns="columns"
            @on-table-props-changed="reloadTable">
        </data-table>
    </div>
</template>

<script>
import {BASE_URL, HTTP_CREATED, HTTP_OK, HTTP_UNPROCESSABLE_ENTITY} from "../../common";
import moment from "moment";
import ActionButton from "./chield-components/ActionButtonGroup";
import CouponList from "./chield-components/CouponList";

export default {
    data() {
        return {
            data: {},
            url: `${BASE_URL}/get-all-applied-coupons`,
            tableProps: {
                search: '',
                length: 10,
                column: 'id',
                dir: 'desc',
                coupon_id: this.$route.params.coupon_id
            },
            columns: [
                {
                    label: 'ID',
                    name: 'id',
                    orderable: true,
                },
                {
                    label: 'Category',
                    name: 'category.title',
                    orderable: false,
                },
                {
                    label: 'Course',
                    name: 'title',
                    orderable: false,
                },
                {
                    label: 'CourseFee',
                    name: 'course_fee',
                    orderable: false,
                },
                {
                    label: 'Coupons',
                    component: CouponList,
                    orderable: false,
                }
            ],
            payload: {},
            courseList: [],
            courseCategoryList: [],
            couponList: [],
            appliedCouponList: [],
            courseCategoryId: null,
            courseId: null,
            couponCode: null
        }
    },
    methods: {
        reloadTable(tableProps) {
            this.getAppliedCouponList(this.url, tableProps)
        },
        getCourseCategory() {
            axios.get(`${BASE_URL}/course-categories`)
                .then(response => {
                    console.log(response);
                    this.courseCategoryList = response.data.data;
                })
                .catch(errors => {
                    console.log(errors);
                })
        },
        getCourseList(categoryId) {
            axios.get(`${BASE_URL}/courses`, {
                params: {
                    category_id: categoryId
                }
            })
                .then(response => {
                    console.log(response);
                    this.courseList = response.data.data;
                })
                .catch(errors => {
                    console.log(errors);
                })
        },
        getCouponList(categoryId) {
            axios.get(`${BASE_URL}/get-all-coupons`)
                .then(response => {
                    console.log(response);
                    this.couponList = response.data.data;
                })
                .catch(errors => {
                    console.log(errors);
                })
        },
        getAppliedCouponList(url = this.url, options = this.tableProps) {
            axios.get(url, {
                params: options
            }).then(response => {
                console.log(response);
                this.data = response.data;
            })
                .catch(errors => {
                    console.log(errors);
                })
        },
        applyCoupon() {
            const payload = {
                category_id: this.courseCategoryId,
                course_id: this.courseId,
                coupon_code: this.couponCode,
            }
            this.axios
                .post(`${BASE_URL}/apply-coupon`, payload)
                .then((response) => {
                    this.getAppliedCouponList(this.url, this.tableProps)
                    const responseData = response.data;
                    this.$toast.success(responseData.message);
                }).catch((errors) => {
                const errorMessage = errors.response;
                if (errorMessage.status === HTTP_UNPROCESSABLE_ENTITY && errorMessage.data.error) {
                    errorMessage.data.error.map((element) => {
                        this.$toast.error(element);
                    })
                } else {
                    this.$toast.error(errorMessage.data.message);
                }
            })

        }
    },
    created() {
        this.getCourseCategory()
        this.getCourseList()
        this.getCouponList()
        this.getAppliedCouponList();
    },
    watch: {
        courseCategoryId: function (newValue, OldValue) {
            console.log(newValue);
            this.getCourseList(newValue)
        }
    }
}
</script>
