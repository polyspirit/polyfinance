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
        Schema::create('flow_tag', function (Blueprint $table) {
            $table->id();
            $table->foreignId('flow_id')->constrained(
                table: 'flows',
                indexName: 'flow_tag_flow_id'
            )
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('tag_id')->constrained(
                table: 'tags',
                indexName: 'flow_tag_tag_id'
            )
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flow_tag');
    }
};
