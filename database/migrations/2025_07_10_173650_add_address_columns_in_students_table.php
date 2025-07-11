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
        Schema::table('students', function (Blueprint $table) {
            $table->string('address')->nullable()->after('email');
            $table->string('address_number')->nullable()->after('address');
            $table->string('address_complement')->nullable()->after('address_number');
            $table->string('city')->nullable()->after('address_complement');
            $table->string('state', 2)->nullable()->after('city');
            $table->string('postal_code', 10)->nullable()->after('state');
            $table->string('external_id')->nullable()->after('postal_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn([
                'address',
                'address_number',
                'address_complement',
                'city',
                'state',
                'postal_code',
                'external_id',
            ]);
        });
    }
};
