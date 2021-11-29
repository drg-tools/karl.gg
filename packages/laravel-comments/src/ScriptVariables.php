<?php

namespace Hazzard\Comments;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Auth;

class ScriptVariables
{
    /**
     * @var array
     */
    protected static $variables = [];

    /**
     * Get the JavaScript variables.
     *
     * @return array
     */
    public static function all()
    {
        $config = config('comments');
        $pageId = request('page_id');
        $commentableId = request('commentable_id');
        $commentableType = request('commentable_type');
        $broadcastDriver = config('broadcasting.default');

        static::add('data.user', $user = Auth::user());

        return array_merge([
            'canPost' => $user ? true : $config['allow_guests'],
            'canModerate' => $user ? $user->can('moderate', Comment::class) : false,
            'loggedIn' => ! is_null($user),
            'captchaGuest' => $config['captcha_guest'],
            'captchaAuth' => $config['captcha_auth'],
            'maxLength' => $config['max_length'],
            'defaultOrder' => $config['default_order'],
            'allowReplies' => $config['allow_replies'],
            'allowVotes' => $config['allow_votes'],
            'orderOptions' => ['latest', 'oldest', 'best'],
            'locale' => app()->getLocale(),
            'translations' => trans('comments::javascript'),
            'permalink' => request('permalink', url()),
            'page' => (int) request('page', 1),
            'target' => (int) request('target'),
            'pageId' => $pageId,
            'commentableId' => $commentableId,
            'commentableType' => $commentableType,
            'recaptchaKey' => config('services.recaptcha.key'),
            'emoji' => $config['emoji'],
            'pageHash' => md5($pageId ?: $commentableId.'|'.str_replace('.', '\\', $commentableType)),
            'broadcast' => $config['broadcast'],
            'broadcasting' => [
                'driver' => $broadcastDriver === 'redis' ? 'socket.io' : $broadcastDriver,
                'pusher' => array_merge(
                    ['key' => config('broadcasting.connections.pusher.key')],
                    config('broadcasting.connections.pusher.options', [])
                ),
            ],
        ], static::$variables);
    }

    /**
     * Add a JavaScript variable.
     *
     * @param  array|string $key
     * @param  mixed $value
     * @return void
     */
    public static function add($key, $value = null)
    {
        if (is_array($key)) {
            foreach ($key as $innerKey => $innerValue) {
                Arr::set(static::$variables, $innerKey, $innerValue);
            }
        } else {
            Arr::set(static::$variables, $key, $value);
        }
    }

    /**
     * Get a JavaScript variable.
     *
     * @param  string $key
     * @return mixed
     */
    public static function get($key)
    {
        return Arr::get(static::$variables, $key);
    }

    /**
     * Render as a HTML string.
     *
     * @param  string $varName
     * @return \Illuminate\Support\HtmlString
     */
    public static function render($varName = 'config')
    {
        return new HtmlString('<script>window.'.$varName.' = '.json_encode(static::all()).';</script>');
    }

    /**
     * Get the path to a versioned Mix file.
     *
     * @param  string $path
     * @param  string $manifestDirectory
     * @return \Illuminate\Support\HtmlString
     *
     * @throws \Exception
     */
    public static function mix($path, $manifestDirectory = '')
    {
        if (app()->runningUnitTests()) {
            return;
        }

        static $manifest;

        if ($manifestDirectory && ! Str::startsWith($manifestDirectory, '/')) {
            $manifestDirectory = "/{$manifestDirectory}";
        }

        if (! $manifest) {
            if (! file_exists($manifestPath = public_path($manifestDirectory.'/mix-manifest.json'))) {
                throw new Exception('The Mix manifest does not exist.');
            }

            $manifest = json_decode(file_get_contents($manifestPath), true);
        }

        if (! Str::startsWith($path, '/')) {
            $path = "/{$path}";
        }

        if (! array_key_exists($path, $manifest)) {
            throw new Exception(
                "Unable to locate Mix file: {$path}. Please check your ".
                'webpack.mix.js output paths and try again.'
            );
        }

        return new HtmlString($manifestDirectory.$manifest[$path]);
    }
}
