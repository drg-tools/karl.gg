<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // OAuth is retired; existing token tables are retained without modification.
    }

    public function down(): void
    {
        // Retired OAuth data is preserved on rollback as well.
    }
};
