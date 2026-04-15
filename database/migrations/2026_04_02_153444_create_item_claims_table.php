<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('item_claims', function (Blueprint $table) {
            $table->id();
            $table->foreignId('found_item_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('claim_reason');
            $table->string('proof_photo')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
            
            $table->index(['found_item_id', 'status']);
            $table->unique(['found_item_id', 'user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('item_claims');
    }
};