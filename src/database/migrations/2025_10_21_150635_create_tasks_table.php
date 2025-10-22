<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(table: 'tasks',
            callback: function (Blueprint $table): void {
                $table->id();

                $table->string(column: 'title', length: 80);
                $table->string(column: 'description', length: 255)->nullable();
                $table->boolean(column: 'status')->default(value: true);

                $table->timestamps(precision: 6);
            }
        );

        Schema::table(table: 'tasks',
            callback: function (Blueprint $table): void {
                $table->comment(comment: 'Задачи');

                $table->index(columns: 'status');
                $table->index(columns: 'created_at');
            }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(table: 'tasks');
    }
};
