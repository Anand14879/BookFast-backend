{{-- This file is used for menu items by any Backpack v6 theme --}}
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>

<x-backpack::menu-item title="Facilities" icon="la la-question" :link="backpack_url('facility')" />
<x-backpack::menu-item title="Bookings" icon="la la-question" :link="backpack_url('booking')" />
<x-backpack::menu-item title="Slots" icon="la la-question" :link="backpack_url('slot')" />
<x-backpack::menu-item title="Payments" icon="la la-question" :link="backpack_url('payment')" />
<x-backpack::menu-item title="Users" icon="la la-question" :link="backpack_url('user')" />
{{-- <x-backpack::menu-item title="Dashboard controllers" icon="la la-question" :link="backpack_url('dashboard-controller')" /> --}}
<x-backpack::menu-item title="Contacts" icon="la la-question" :link="backpack_url('contact')" />