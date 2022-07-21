<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use JamesDordoy\LaravelVueDatatable\Http\Resources\DataTableCollectionResource;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

/**
 *
 */
class CourseController extends Controller
{

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function courseList(Request $request): JsonResponse
    {
        $courseQueryBuilder = Course::query();
        if ($request->get('category_id')) {
            $courseQueryBuilder->where('course_category_id', $request->get('category_id'));
        }
        return response()->json([
            "data" => $courseQueryBuilder->get(),
            "status_code" => ResponseAlias::HTTP_OK,
            "message" => "courses"
        ], ResponseAlias::HTTP_OK);
    }

    /**
     * @return JsonResponse
     */
    public function categoryList(): JsonResponse
    {
        $courseCategoryQueryBuilder = CourseCategory::query();
        return response()->json([
            "data" => $courseCategoryQueryBuilder->get(),
            "status_code" => ResponseAlias::HTTP_OK,
            "message" => "courseCategory"
        ], ResponseAlias::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return DataTableCollectionResource
     */
    public function getAllAppliedCouponList(Request $request): DataTableCollectionResource
    {
        $length = $request->input('length') ?? 10;
        $sortBy = $request->input('column');
        $orderBy = $request->input('dir');
        $searchValue = $request->input('search');
        $couponID = $request->input('coupon_id');
        $coupons = Course::eloquentQuery($sortBy, $orderBy, $searchValue)->with(['category', 'coupons'])->paginate($length);
        return new DataTableCollectionResource($coupons);
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function applyCoupon(Request $request): JsonResponse
    {
        $response = [];
        DB::beginTransaction();
        try {
            $validateData = $this->applyCouponValidation($request)->validate();
            $categoryId = $validateData['category_id'];
            $courseId = $validateData['course_id'];
            $couponCode = $validateData['coupon_code'];

            if ($categoryId && !empty($courseId)) {
                $course = Course::findOrFail($courseId);
                /** One Coupon Is for One Course */
                $course->applyUniqueCoupon($couponCode);
            } else {
                $courses = Course::where('course_category_id', $categoryId)->get();
                foreach ($courses as $course) {
                    $course->applyUniqueCoupon($couponCode);
                }
            }
            $response = [
                "status_code" => ResponseAlias::HTTP_CREATED,
                "message" => "Successfully applied"
            ];
            DB::commit();
        } catch (ValidationException $exception) {
            DB::rollBack();
            $response = [
                "error" => array_values($exception->errors())[0],
                "status_code" => ResponseAlias::HTTP_UNPROCESSABLE_ENTITY,
                "message" => "Validation Exception"
            ];
        } catch (\Exception $exception) {
            Log::debug("CouponApplyLog: ", (array)$exception);
            $response = [
                "status_code" => ResponseAlias::HTTP_INTERNAL_SERVER_ERROR,
                "message" => $exception->getMessage()
            ];
        }
        return response()->json($response, $response['status_code']);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Validation\Validator
     */
    private function applyCouponValidation(Request $request): \Illuminate\Contracts\Validation\Validator
    {
        $rules = [
            "category_id" => [
                "required",
                "integer",
                "exists:course_categories,id"
            ],
            "course_id" => [
                "nullable",
                "integer",
                "exists:courses,id"
            ],
            "coupon_code" => [
                "required",
                "string"
            ]
        ];
        return Validator::make($request->all(), $rules);
    }
}
