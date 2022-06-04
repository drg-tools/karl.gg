<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>

@can('manage-posts')
    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('post') }}'><i class='nav-icon la la-file-text-o'></i> Posts</a></li>
@endcan

@can('manage-stats')
    <li class="nav-item"><a class="nav-link" href="{{ backpack_url('patch') }}"><i class="la la-cogs nav-icon"></i> Patches</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ backpack_url('mod') }}"><i class="la la-puzzle-piece nav-icon"></i> Mods</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ backpack_url('overclock') }}"><i class="la la-tachometer-alt nav-icon"></i> Overclocks</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ backpack_url('gun') }}"><i class="la la-rocket nav-icon"></i> Guns</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ backpack_url('equipment') }}"><i class="la la-tshirt nav-icon"></i> Equipment</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ backpack_url('equipment-mod') }}"><i class="la la-tools nav-icon"></i> Equipment Mods</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ backpack_url('throwable') }}"><i class="la la-futbol nav-icon"></i> Throwables</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ backpack_url('loadout') }}"><i class="la la-address-card nav-icon"></i> Loadouts</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ backpack_url('character') }}"><i class="la la-running nav-icon"></i> Characters</a></li>
@endcan

@can('manage-comments')
<li class="nav-item"><a class="nav-link" href="/comments/admin"><i class="la la-comments nav-icon"></i> Comment Dashboard</a></li>
@endcan

<!-- Users, Roles, Permissions -->
@can('manage-users')
<li class="nav-item nav-dropdown">
	<a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-group"></i> Authentication</a>
	<ul class="nav-dropdown-items">
	  <li class="nav-item"><a class="nav-link" href="{{ backpack_url('user') }}"><i class="nav-icon la la-user"></i> <span>Users</span></a></li>
	  <li class="nav-item"><a class="nav-link" href="{{ backpack_url('role') }}"><i class="nav-icon la la-group"></i> <span>Roles</span></a></li>
	  <li class="nav-item"><a class="nav-link" href="{{ backpack_url('permission') }}"><i class="nav-icon la la-key"></i> <span>Permissions</span></a></li>
	</ul>
</li>
@endcan
