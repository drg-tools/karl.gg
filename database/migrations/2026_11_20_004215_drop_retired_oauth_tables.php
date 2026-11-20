<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('oauth_refresh_tokens');
        Schema::dropIfExists('oauth_access_tokens');
        Schema::dropIfExists('oauth_auth_codes');
        Schema::dropIfExists('oauth_device_codes');
        Schema::dropIfExists('oauth_personal_access_clients');
        Schema::dropIfExists('oauth_clients');
    }

    /**
     * Restore a database backup to recover retired OAuth tables and data.
     */
    public function down(): void {}

    public function getConnection(): ?string
    {
        return $this->connection ?? config('passport.connection');
    }
};
