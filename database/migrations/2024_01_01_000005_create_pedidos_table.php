<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->decimal('total', 10, 2);
            $table->enum('status', ['new', 'in_review', 'changes_requested', 'approved', 'rejected'])->default('new');
            $table->dateTime('created_date');
            $table->dateTime('updated_date');
            $table->foreignId('requester_id')->constrained('requesters');
            $table->foreignId('group_id')->constrained('groups');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
