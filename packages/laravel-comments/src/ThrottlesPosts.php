<?php

namespace Hazzard\Comments;

use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiter;

trait ThrottlesPosts
{
    /**
     * Determine if should throttle comment posts.
     *
     * @param  \Illuminate\Http\Request $request
     * @return bool
     */
    public function shouldThrottlePosts(Request $request)
    {
        if ($request->user() && $request->user()->can('moderate', Comment::class)) {
            return false;
        }

        return config('comments.throttle');
    }

    /**
     * Determine if the user has too many comment post attempts.
     *
     * @param  \Illuminate\Http\Request $request
     * @return bool
     */
    public function hasTooManyPostAttempts(Request $request)
    {
        $maxAttempts = config('comments.throttle_max_attempts');
        $decayMinutes = config('comments.throttle_decay_minutes');

        return $this->limiter()->tooManyAttempts(
            $this->throttleKey($request),
            $maxAttempts,
            $decayMinutes
        );
    }

    /**
     * Increment the comment post attempts.
     *
     * @param  \Illuminate\Http\Request $request
     * @return int
     */
    protected function incrementPostAttempts(Request $request)
    {
        $this->limiter()->hit($this->throttleKey($request));
    }

    /**
     * Redirect the user after determining they are locked out.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendLockoutResponse(Request $request)
    {
        $seconds = $this->limiter()->availableIn(
            $this->throttleKey($request)
        );

        $errors = ['content' => $this->getLockoutMessage($seconds)];

        return response()->json($errors, 429, ['Retry-After' => $seconds]);
    }

    /**
     * Clear the comment post locks.
     *
     * @param  \Illuminate\Http\Request $request
     * @return void
     */
    protected function clearAttempts(Request $request)
    {
        $this->limiter()->clear($this->throttleKey($request));
    }

    /**
     * Get the throttle key for the given request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function throttleKey(Request $request)
    {
        if ($request->user()) {
            return $request->user()->getAuthIdentifier().'|'.$request->ip();
        }

        return $request->ip();
    }

    /**
     * Get the rate limiter instance.
     *
     * @return \Illuminate\Cache\RateLimiter
     */
    protected function limiter()
    {
        return app(RateLimiter::class);
    }

    /**
     * Get the lockout error message.
     *
     * @param  int $seconds
     * @return string
     */
    protected function getLockoutMessage($seconds)
    {
        return trans('comments::messages.throttle', compact('seconds'));
    }
}
