import AllCoupon from './components/coupon/AllCoupon.vue';
import AddCoupon from './components/coupon/AddCoupon.vue';
import EditCoupon from './components/coupon/EditCoupon.vue';
import ApplyCoupon from "./components/coupon/ApplyCoupon";
import WhereCouponApplied from "./components/coupon/chield-components/WhereCouponApplied";

export const routes = [
    {
        name: 'home',
        path: '/',
        component: AllCoupon
    },
    {
        name: 'add',
        path: '/add',
        component: AddCoupon
    },
    {
        name: 'edit',
        path: '/edit/:id',
        component: EditCoupon
    },
    {
        name: 'apply-coupon',
        path: '/apply-coupon',
        component: ApplyCoupon
    },
    {
        name: 'where-coupon-applied',
        path: '/where-coupon-applied/:code',
        component: WhereCouponApplied
    }
];
