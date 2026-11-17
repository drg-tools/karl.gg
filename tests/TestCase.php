<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        // Tests must not depend on, or change, the application's OAuth key files.
        static $keys;
        if ($keys === null) {
            $key = openssl_pkey_new(['private_key_bits' => 2048, 'private_key_type' => OPENSSL_KEYTYPE_RSA]);
            openssl_pkey_export($key, $privateKey);
            $keys = [$privateKey, openssl_pkey_get_details($key)['key']];
        }
        config(['passport.private_key' => $keys[0], 'passport.public_key' => $keys[1]]);
    }
}
