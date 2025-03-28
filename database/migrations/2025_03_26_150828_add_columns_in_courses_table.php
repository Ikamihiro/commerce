<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->string('color')->default('#69b0ef');
            $table->string('buy_button_text')->default('Compre agora o curso');
            $table->integer('students_number')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('color');
            $table->dropColumn('buy_button_text');
            $table->dropColumn('students_number');
        });
    }
};
