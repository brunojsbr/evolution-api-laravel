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
        // Tabela de instâncias
        Schema::create(config('evolution-whatsapp.database.table_prefix') . 'instances', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('status')->default('disconnected');
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        // Tabela de mensagens
        Schema::create(config('evolution-whatsapp.database.table_prefix') . 'messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instance_id')->constrained(config('evolution-whatsapp.database.table_prefix') . 'instances');
            $table->string('direction')->default('outbound'); // inbound/outbound
            $table->string('message_type'); // text, image, document, etc
            $table->string('from');
            $table->string('to');
            $table->text('content');
            $table->json('metadata')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
        });

        // Tabela de webhooks
        Schema::create(config('evolution-whatsapp.database.table_prefix') . 'webhooks', function (Blueprint $table) {
            $table->id();
            $table->string('event');
            $table->json('payload');
            $table->string('status')->default('pending');
            $table->text('error')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(config('evolution-whatsapp.database.table_prefix') . 'webhooks');
        Schema::dropIfExists(config('evolution-whatsapp.database.table_prefix') . 'messages');
        Schema::dropIfExists(config('evolution-whatsapp.database.table_prefix') . 'instances');
    }
};
