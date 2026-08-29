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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignUuid('organization_id')->nullable()->after('id')
                ->constrained('organizations')->nullOnDelete();
            $table->string('status', 20)->default('active')->after('password');
            $table->boolean('mfa_enabled')->default(false)->after('status');
            $table->timestampTz('last_login_at')->nullable()->after('mfa_enabled');

            $table->index(['organization_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['organization_id', 'status']);
            $table->dropConstrainedForeignId('organization_id');
            $table->dropColumn(['status', 'mfa_enabled', 'last_login_at']);
        });
    }
};
