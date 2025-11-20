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
            $table->string('username')->unique()->nullable()->after('name');
            $table->text('bio')->nullable()->after('email_verified_at');
            $table->string('avatar')->nullable()->after('bio');
            $table->string('cover_photo')->nullable()->after('avatar');
            $table->string('google_id')->nullable()->unique()->after('password');
            $table->string('apple_id')->nullable()->unique()->after('google_id');
            $table->enum('badge_type', ['none', 'verified', 'purple'])->default('none')->after('apple_id');
            $table->timestamp('badge_expires_at')->nullable()->after('badge_type');
            $table->string('stripe_customer_id')->nullable()->after('badge_expires_at');
            $table->boolean('is_active')->default(true)->after('stripe_customer_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'username', 'bio', 'avatar', 'cover_photo', 'google_id',
                'apple_id', 'badge_type', 'badge_expires_at', 'stripe_customer_id', 'is_active'
            ]);
        });
    }
};
