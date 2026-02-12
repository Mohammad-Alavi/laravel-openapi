<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doc_visibility_rules', function (Blueprint $table): void {
            $table->id();
            $table->ulid('ulid')->unique();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('rule_type');
            $table->string('identifier');
            $table->string('visibility');
            $table->timestamps();

            $table->unique(['project_id', 'rule_type', 'identifier']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doc_visibility_rules');
    }
};
