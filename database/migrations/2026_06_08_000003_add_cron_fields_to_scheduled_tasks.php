<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('scheduled_tasks', function (Blueprint $table) {
            $table->string('minute')->default('*')->after('frequency');
            $table->string('hour')->default('*')->after('minute');
            $table->string('day_of_month')->default('*')->after('hour');
            $table->string('month')->default('*')->after('day_of_month');
            $table->string('day_of_week')->default('*')->after('month');
            $table->string('expression')->nullable()->after('day_of_week');
        });
    }

    public function down(): void
    {
        Schema::table('scheduled_tasks', function (Blueprint $table) {
            $table->dropColumn(['minute', 'hour', 'day_of_month', 'month', 'day_of_week', 'expression']);
        });
    }
};
