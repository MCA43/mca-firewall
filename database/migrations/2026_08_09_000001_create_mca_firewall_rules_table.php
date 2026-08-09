<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $table = (string) config('firewall.table', 'mca_firewall_rules');

        Schema::create($table, function (Blueprint $table): void {
            $table->id();
            $table->string('ip', 64);
            $table->string('type', 16); // whitelist|blacklist
            $table->string('label')->nullable();
            $table->text('reason')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('expires_at')->nullable();
            $table->unsignedBigInteger('hits')->default(0);
            $table->timestamp('last_hit_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['type', 'is_active']);
            $table->index('ip');
            $table->index('expires_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists((string) config('firewall.table', 'mca_firewall_rules'));
    }
};
