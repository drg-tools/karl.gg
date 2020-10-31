@component('mail::message')
Hey there!

You have been identified as an active contributor to [karl.gg](karl.gg)!

As you may know, a new Deep Rock Galactic patch has been shipped! With each patch there is a chance that guns, mods,
and overclocks have been updated. In order to make sure [karl.gg](karl.gg) has the freshest loadouts available, all
loadouts created/updated previous to the launch of the latest patch will display a banner indicating it _may_ be old.

Please go through each of the following builds and update any selections that may no longer be preferred. If there
are no changes needed for a particular loadout, simply open it up and re-save it again to remove the banner.

## Your Outdated Builds

@foreach($loadouts as $loadout)
- [{{ $loadout->name }}]({{ route('build.show', $loadout->id) }})
@endforeach

@slot('unsubscribeLink')
{{ $unsubscribeLink }}
@endslot


Rock and Stone, Miner!<br>
Your karl.gg crew
@endcomponent
