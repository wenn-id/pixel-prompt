<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prompts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title')->nullable();
            $table->text('prompt_text');
            $table->text('negative_prompt')->nullable();
            $table->integer('width')->nullable();
            $table->integer('height')->nullable();
            $table->decimal('cfg_scale', 4, 1)->nullable();
            $table->integer('steps')->nullable();
            $table->bigInteger('seed')->nullable();
            $table->string('style_preset')->nullable();
            $table->string('provider')->nullable();
            $table->string('model')->nullable();
            $table->boolean('is_template')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prompts');
    }
};
