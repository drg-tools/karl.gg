<?php

use Illuminate\Database\Migrations\Migration;
use Laravel\Passport\Passport;

return new class extends Migration
{
    public function up(): void
    {
        foreach (Passport::client()->cursor() as $client) {
            $grantTypes = $client->grant_types;

            if (! in_array('personal_access', $grantTypes, true) && ! in_array('password', $grantTypes, true)) {
                continue;
            }

            if (! in_array('client_credentials', $grantTypes, true)) {
                continue;
            }

            $client->timestamps = false;
            $client->forceFill([
                'grant_types' => array_values(array_diff($grantTypes, ['client_credentials'])),
            ])->save();
        }
    }

    public function down(): void
    {
        throw new RuntimeException('Restore the pre-migration OAuth database backup to roll back client grant changes.');
    }
};
