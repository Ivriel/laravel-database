<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations. // file migrations yang udah pernah di execute gabakal di execute lagi. kalau mau ada perubahan harus bikin file migrations baru
     */
    public function up(): void
    {
        Schema::create('counters', function (Blueprint $table) {
            $table->string('id',100)->nullable(false)->primary();
            $table->integer('counter')->nullable(false)->default(0);
            $table->text("description")->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void // buat rollback
    {
        Schema::dropIfExists('counters');
    }
};
