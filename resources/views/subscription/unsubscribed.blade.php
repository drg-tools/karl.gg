@extends('layouts.app')

@section('content')
    <x-auth-card eyebrow="Email preferences" title="You're unsubscribed" lede="Management will no longer send you company emails. Rock and stone!">
        <div class="settings-form__foot">
            <a class="loadout-button" href="{{ url('/') }}">Back to the dashboard</a>
        </div>
    </x-auth-card>
@endsection
