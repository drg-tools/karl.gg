<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Laravel\Passport\Passport;

return new class extends Migration
{
    public function up(): void
    {
        // Fresh installations already have Passport 13's schema. Keep existing IDs.
        if (! Schema::hasColumn('oauth_clients', 'user_id')) {
            return;
        }

        Schema::table('oauth_clients', function (Blueprint $table) {
            $table->nullableMorphs('owner');
            $table->text('redirect_uris')->nullable();
            $table->text('grant_types')->nullable();
        });

        foreach (Passport::client()->cursor() as $client) {
            $client->timestamps = false;
            $client->forceFill([
                'owner_id' => $client->user_id,
                'owner_type' => $client->user_id ? config('auth.providers.users.model') : null,
                'redirect_uris' => $client->redirect_uris,
                'grant_types' => $client->grant_types,
            ])->save();
        }

        Schema::table('oauth_clients', function (Blueprint $table) {
            $table->dropColumn(['user_id', 'redirect', 'personal_access_client', 'password_client']);
        });
    }

    public function down(): void
    {
        // The release also hashes client secrets; rollback requires the pre-upgrade backup.
        throw new RuntimeException('Restore the pre-upgrade OAuth database backup to roll back Passport 13.');
    }
};
