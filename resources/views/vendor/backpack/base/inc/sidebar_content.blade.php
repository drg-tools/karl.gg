<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('mod') }}"><i class="la la-cog nav-icon"></i> Mods</a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('mod-stat') }}"><i class="la la-puzzle-piece nav-icon"></i> Mod Stats</a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('overclock') }}"><i class="la la-tachometer-alt nav-icon"></i> Overclock Stats</a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('gun') }}"><i class="la la-rocket nav-icon"></i> Guns</a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('build') }}"><i class="la la-address-card nav-icon"></i> Builds</a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('character') }}"><i class="la la-running nav-icon"></i> Characters</a></li>
<!-- Users, Roles, Permissions -->
<li class="nav-item nav-dropdown">
	<a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-group"></i> Authentication</a>
	<ul class="nav-dropdown-items">
	  <li class="nav-item"><a class="nav-link" href="{{ backpack_url('user') }}"><i class="nav-icon la la-user"></i> <span>Users</span></a></li>
	  <li class="nav-item"><a class="nav-link" href="{{ backpack_url('role') }}"><i class="nav-icon la la-group"></i> <span>Roles</span></a></li>
	  <li class="nav-item"><a class="nav-link" href="{{ backpack_url('permission') }}"><i class="nav-icon la la-key"></i> <span>Permissions</span></a></li>
	</ul>
</li>