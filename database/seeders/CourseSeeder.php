<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        CourseCategory::query()->truncate();
        Course::query()->truncate();
        CourseCategory::insert([
            [
                "id" => 1,
                "title" => "CSE",
                "title_en" => "CSE",
            ],
            [
                "id" => 2,
                "title" => "EEE",
                "title_en" => "EEE"
            ]
        ]);
        Course::insert([
            [
                "course_category_id" => 1,
                "title" => "Java",
                "title_en" => "Java",
                "course_fee" => 1200
            ],
            [
                "course_category_id" => 1,
                "title" => "PHP",
                "title_en" => "PHP",
                "course_fee" => 900
            ],
            [
                "course_category_id" => 1,
                "title" => "JavaScript",
                "title_en" => "JavaScript",
                "course_fee" => 1010
            ],
            [
                "course_category_id" => 2,
                "title" => "Circuit Design",
                "title_en" => "Circuit Design",
                "course_fee" => 1500
            ],
            [
                "course_category_id" => 2,
                "title" => "Digital Electronics",
                "title_en" => "Digital Electronics",
                "course_fee" => 9000
            ],
            [
                "course_category_id" => 2,
                "title" => "Signals and Systems",
                "title_en" => "Signals and Systems",
                "course_fee" => 10010
            ]
        ]);
        Schema::enableForeignKeyConstraints();
    }
}
