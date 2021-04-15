<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddItsJobFieldsToCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('functional_area',250)->nullable()->after('location');
            $table->string('working_hours',250)->nullable()->after('functional_area');
            $table->integer('work_from_home')->default('0')->after('working_hours');
            $table->integer('number_of_vacancies')->default('1')->after('work_from_home');
            $table->string('salary_currency',250)->default('USD')->after('number_of_vacancies');
            $table->string('minimum_salary',250)->nullable()->after('salary_currency');
            $table->string('maximum_salary',250)->nullable()->after('minimum_salary');
            $table->string('job_benefits',250)->nullable()->after('maximum_salary');
            $table->string('gender_preference',250)->nullable()->after('job_benefits');
            $table->string('minimum_age_group',250)->default('18')->after('gender_preference');
            $table->string('maximum_age_group',250)->nullable()->after('minimum_age_group');
            $table->string('education_qualification',250)->nullable()->after('maximum_age_group');
            $table->string('experience_range',250)->nullable()->after('education_qualification');
            $table->string('organisation_name',250)->nullable()->after('minimum_age_group');
            $table->string('organisation_email',250)->nullable()->after('organisation_name');
            $table->string('organisation_description',250)->nullable()->after('organisation_email');
            $table->string('organisation_additional_contact_details',250)->nullable()->after('organisation_description');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            //
        });
    }
}
