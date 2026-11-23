@extends('layouts.app')

@php
    $hasUnread = auth()->user()->unreadNotifications()->exists();
    $headlines = [
        \App\Notifications\NotificationType::BuildComment->value => ':author commented on your build :build',
        \App\Notifications\NotificationType::CommentReply->value => ':author replied to your comment on :build',
    ];
@endphp

@section('content')
    <div class="loadout-page notifications">
        <header class="browse-header">
            <div>
                <p class="loadout-eyebrow">Account</p>
                <h1 class="browse-header__title">Notifications</h1>
                <p class="browse-header__lede">
                    Comments on your builds and replies to your comments.
                    <a class="browse-clear" href="{{ route('account.settings') }}#settings-notifications-title">Choose what you're notified about</a>
                </p>
            </div>

            @if ($hasUnread)
                <form method="POST" action="{{ route('notifications.read-all') }}">
                    @csrf
                    <button class="loadout-button is-secondary" type="submit">Mark all as read</button>
                </form>
            @endif
        </header>

        @if ($notifications->isEmpty())
            <div class="loadout-panel notifications-empty">
                <p>Nothing yet. When someone comments on one of your builds or replies to you, it shows up here.</p>
            </div>
        @else
            <ol class="loadout-panel notifications-list">
                @foreach ($notifications as $notification)
                    <li @class(['notifications-item', 'is-unread' => $notification->unread()])>
                        <a class="notifications-item__link" href="{{ route('notifications.show', $notification->id) }}">
                            <span class="notifications-item__headline">
                                @if ($notification->unread())
                                    <span class="sr-only">Unread:</span>
                                @endif
                                {!! strtr(e($headlines[$notification->type] ?? ':author commented on :build'), [
                                    ':author' => '<strong>'.e($notification->data['author_name'] ?? 'Someone').'</strong>',
                                    ':build' => '<strong>'.e($notification->data['loadout_name'] ?? 'a build').'</strong>',
                                ]) !!}
                            </span>
                            @if (! empty($notification->data['excerpt']))
                                <span class="notifications-item__excerpt">“{{ $notification->data['excerpt'] }}”</span>
                            @endif
                            <time class="notifications-item__time" datetime="{{ $notification->created_at->toIso8601String() }}">{{ $notification->created_at->diffForHumans() }}</time>
                        </a>
                    </li>
                @endforeach
            </ol>

            {{ $notifications->onEachSide(1)->links('loadouts.partials.pagination', ['label' => 'Notification pages']) }}
        @endif
    </div>
@endsection
