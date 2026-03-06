<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->string('certificate_bg_image')->nullable()->after('has_certificate');
            $table->integer('certificate_name_x')->nullable()->after('certificate_bg_image');
            $table->integer('certificate_name_y')->nullable()->after('certificate_name_x');
            $table->integer('certificate_name_font_size')->default(48)->after('certificate_name_y');
            $table->string('certificate_name_color', 20)->default('#1a1a2e')->after('certificate_name_font_size');
            $table->string('certificate_name_font', 100)->default('Great Vibes')->after('certificate_name_color');
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn([
                'certificate_bg_image',
                'certificate_name_x',
                'certificate_name_y',
                'certificate_name_font_size',
                'certificate_name_color',
                'certificate_name_font',
            ]);
        });
    }
};
