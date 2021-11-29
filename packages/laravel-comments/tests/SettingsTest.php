<?php

namespace Hazzard\Comments\Tests;

use Hazzard\Comments\Contracts\Settings;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SettingsTest extends TestCase
{
    /**
     * @var \Hazzard\Comments\Contracts\Settings
     */
    protected $settings;

    public function setUp(): void
    {
        parent::setUp();

        config(['comments.settings' => true]);

        $this->settings = $this->app->make(Settings::class);
    }

    /** @test */
    public function it_can_determine_if_has_settings()
    {
        $this->assertFalse($this->settings->has('foo'));

        DB::table('comment_settings')->insert(['key' => 'foo', 'value' => 'bar']);

        $this->assertTrue($this->settings->has('foo'));
    }

    /** @test */
    public function it_can_set_settings()
    {
        $this->settings->set('string', 'bar');
        $this->settings->set('array', ['foo', 'bar']);

        $this->assertDatabaseHas('comment_settings', [
            'key' => 'string',
            'value' => serialize('bar'),
        ])
        ->assertDatabaseHas('comment_settings', [
            'key' => 'array',
            'value' => serialize(['foo', 'bar']),
        ]);
    }

    /** @test */
    public function it_can_update_settings()
    {
        $this->settings->set('foo', 'bar');
        $this->settings->set('foo', 'baz');

        $this->assertDatabaseHas('comment_settings', [
            'key' => 'foo',
            'value' => serialize('baz'),
        ]);
    }

    /** @test */
    public function it_can_get_settings()
    {
        $this->settings->set('string', 'foo');
        $this->settings->set('integer', 1);
        $this->settings->set('array', ['foo' => 'bar']);

        $this->assertEquals('foo', $this->settings->get('string'));
        $this->assertEquals(1, $this->settings->get('integer'));
        $this->assertArrayHasKey('foo', $this->settings->get('array'));
    }

    /** @test */
    public function it_can_forget_settings()
    {
        $this->settings->set('foo', 'bar');
        $this->settings->forget('foo');

        $this->assertDatabaseMissing('comment_settings', [
            'key' => 'foo',
        ]);
    }

    /** @test */
    public function it_can_cache_settings()
    {
        $this->settings->set('foo', 'bar');
        $this->settings->get('foo');

        $this->assertTrue(Cache::has('comments.settings.foo'));

        DB::table('comment_settings')
            ->where('key', 'foo')
            ->update(['value' => serialize('updated')]);

        $this->settings->get('foo');

        $this->settings->forget('foo');

        $this->assertFalse(Cache::has('comments.settings.foo'));
    }

    /** @test */
    public function it_can_override_the_config_on_service_provider_boot()
    {
        config(['comments.allow_guests' => true]);

        $this->settings->set('allow_guests', false);

        $this->app->getProvider(\Hazzard\Comments\CommentsServiceProvider::class)->boot();

        $this->assertFalse(config('comments.allow_guests'));
    }
}
