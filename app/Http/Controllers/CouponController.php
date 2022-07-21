<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Client\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use JamesDordoy\LaravelVueDatatable\Http\Resources\DataTableCollectionResource;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Tasmidur\Coupon\Facades\Coupons;

class CouponController extends Controller
{

    public function index(Request $request): DataTableCollectionResource
    {
        $length = $request->input('length');
        $sortBy = $request->input('column');
        $orderBy = $request->input('dir');
        $data = Coupons::getCouponListWithPagination($length, $sortBy, $orderBy);
        return new DataTableCollectionResource($data);
    }

    /**
     * @return JsonResponse
     */
    public function couponList(): JsonResponse
    {
        $coupon = Coupons::getCouponList();
        return response()->json([
            "data" => $coupon,
            "status_code" => ResponseAlias::HTTP_OK,
            "message" => "Coupons"
        ], ResponseAlias::HTTP_OK);
    }

    public function getWhereApplyCouponCode(string $code): JsonResponse
    {
        $coupon = Coupons::whereApplyCoupon($code);
        return response()->json([
            "data" => $coupon,
            "status_code" => ResponseAlias::HTTP_OK,
            "message" => "List of where apply couponCode"
        ], ResponseAlias::HTTP_OK);
    }

    public function store(Request $request): JsonResponse
    {
        $response = [];
        try {
            $validateData = $this->dataValidation($request)->validate();
            $couponType = $validateData['coupon_type'];
            $price = $validateData['price'];
            Log::info(Carbon::create($validateData['expired_at']));
            $expiredAt = !empty($validateData['expired_at']) ? Carbon::create($validateData['expired_at']) : null;
            $coupon = Coupons::createCoupon($couponType, $price, $expiredAt);
            $response = [
                "data" => $coupon,
                "status_code" => ResponseAlias::HTTP_CREATED,
                "message" => "Successfully added"
            ];
        } catch (ValidationException $exception) {
            $response = [
                "error" => array_values($exception->errors())[0],
                "status_code" => ResponseAlias::HTTP_UNPROCESSABLE_ENTITY,
                "message" => "Validation Exception"
            ];
        } catch (\Exception $exception) {
            $response = [
                "status_code" => $exception->getCode() !== 0 ? $exception->getCode() : ResponseAlias::HTTP_INTERNAL_SERVER_ERROR,
                "message" => $exception->getMessage()
            ];
        }
        return response()->json($response, $response['status_code']);
    }

    public function read(int $id): JsonResponse
    {
        $coupon = Coupons::getCoupon($id);
        return response()->json([
            "data" => $coupon,
            "status_code" => ResponseAlias::HTTP_OK,
            "message" => "Coupon"
        ], ResponseAlias::HTTP_OK);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $response = [];
        try {
            $validateData = $this->dataValidation($request)->validate();
            Coupons::updateCoupon($validateData, $id);
            $response = [
                "status_code" => ResponseAlias::HTTP_OK,
                "message" => "Successfully deleted"
            ];
        } catch (ValidationException $exception) {
            $response = [
                "error" => $exception->errors(),
                "status_code" => ResponseAlias::HTTP_UNPROCESSABLE_ENTITY,
                "message" => "Validation Exception"
            ];
        } catch (\Exception $exception) {
            $response = [
                "status_code" => $exception->getCode() !== 0 ? $exception->getCode() : ResponseAlias::HTTP_INTERNAL_SERVER_ERROR,
                "message" => $exception->getMessage()
            ];
        }
        return response()->json($response, $response['status_code']);
    }

    public function delete(int $id): JsonResponse
    {
        $response = [];
        try {
            Coupons::deleteCoupon($id);
            $response = [
                "status_code" => ResponseAlias::HTTP_OK,
                "message" => "Successfully deleted"
            ];
        } catch (\Exception $exception) {
            $response = [
                "status_code" => $exception->getCode() !== 0 ? $exception->getCode() : ResponseAlias::HTTP_INTERNAL_SERVER_ERROR,
                "message" => $exception->getMessage()
            ];
        }
        return response()->json($response, $response['status_code']);
    }

    private function dataValidation(Request $request, int $id = null): \Illuminate\Contracts\Validation\Validator
    {
        $rules = [
            "coupon_type" => [
                "required",
                "string",
                Rule::in(['FIXED_PRICE', 'DISCOUNT_PRICE'])
            ],
            "price" => [
                "required",
                "numeric",
                "gt:-1",
                Rule::when($request->get('coupon_type') === 'DISCOUNT_PRICE',
                    [
                        "gte:0",
                        "lte:100"
                    ]
                )
            ],
            "expired_at" => [
                "nullable",
                "date"
            ]
        ];
        return Validator::make($request->all(), $rules);
    }

}
