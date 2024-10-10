<?php
  if (is_null(Session::get('ret'))) {
    return redirect('/logout');
  }
  $dev = Session::get('ret')[0]['dev'];
  $relator = Session::get('ret')[0]['relator'];
  $tester = Session::get('ret')[0]['tester'];
  $admin = Session::get('ret')[0]['admin'];
  $avatar = auth('sanctum')->user()->avatar;
?>
<x-splade-toggle>
    <nav class="bg-white border-b border-gray-100">
        <!-- Primary Navigation Menu -->
        <div class="max-w-1xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <!-- Logo -->
                    <div class="shrink-0 flex items-center">
                        <x-nav-link :href="route('dashboard')">
                            <img src="<?php echo url(''); ?>/assets/logo.jpg" width="70" dalt="logo!">
                        </x-nav-link>
                    </div>
                    
                    <!-- Dashboad -->
                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                    </div>

                    <!-- My Tickets -->
                    @if ($dev == '1' || $relator == '1')
                        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                            <x-nav-link :href="route('tickets.mytickets')" :active="request()->routeIs('tckets.mytickets')">
                                {{ __('My Tickets') }}
                            </x-nav-link>
                        </div>
                    @endif

                    <!-- Testing -->
                    @if ($tester == '1')
                        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                            <x-nav-link :href="route('tickets.testing')" :active="request()->routeIs('tckets.testing')">
                                {{ __('Testing') }}
                            </x-nav-link>
                        </div>
                    @endif

                    <!-- New Ticket -->
                    @if ($relator == '1')
                        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                            <x-nav-link href="{{ route('tickets.show', base64_encode(0)) }}" :active="request()->routeIs('tickets.show')">
                                {{ __('New Ticket') }}
                            </x-nav-link>
                        </div>
                    @endif

                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                        <x-nav-link href="{{ route('sprints.index', base64_encode(0)) }}" :active="request()->routeIs('sprints.index')">
                            {{ __('Sprint') }}
                        </x-nav-link>
                    </div>

                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                        <x-nav-link href="{{ route('tickets.index') }}" :active="request()->routeIs('tickets.index')">
                            {{ __('Tickets') }}
                        </x-nav-link>
                    </div>

                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                        <x-nav-link href="{{ route('documents.index') }}" :active="request()->routeIs('documents.index')">
                            {{ __('Documents') }}
                        </x-nav-link>
                    </div>

                    <!-- Admin Dropdown -->
                    @if ($admin == 1)
                        <div class="hidden sm:flex sm:items-center sm:ml-6">
                            <x-dropdown placement="bottom-end">
                                <x-slot name="trigger">
                                    <button class="flex items-center text-sm font-medium text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition duration-150 ease-in-out">
                                        <div>{{ __('Admin') }}</div>

                                        <div class="ml-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </x-slot>

                                <x-slot name="content">

                                    <x-dropdown-link :href="route('projects.index')">
                                        {{ __('Projects') }}
                                    </x-dropdown-link>

                                    <x-dropdown-link :href="route('users.index')">
                                        {{ __('Users') }}
                                    </x-dropdown-link>

                                    <x-dropdown-link :href="route('tipodocs.index')">
                                        {{ __('Type of Documents') }}
                                    </x-dropdown-link>

                                    {{-- <x-dropdown-link :href="route('config.index')">
                                        {{ __('Configurations') }}
                                    </x-dropdown-link> --}}

                                </x-slot>
                            </x-dropdown>
                        </div>
                    @endif

                </div>

                <!-- Settings Dropdown -->
                <div class="hidden sm:flex sm:items-center sm:ml-6">
                    <x-dropdown placement="bottom-end">
                        <x-slot name="trigger">
                            <button class="flex items-center text-sm font-medium text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition duration-150 ease-in-out">
                                <div class="inline-flex">
                                    @if($avatar == 1)
                                        <img src="<?php echo url(''); ?>/avatar/4043229_afro_avatar_male_man_icon.png" width="30" dalt="">
                                    @elseif($avatar == 2)
                                        <img src="<?php echo url(''); ?>/avatar/4043240_avatar_bad_breaking_chemisrty_heisenberg_icon.png" width="30" dalt="">
                                    @elseif($avatar == 3)
                                        <img src="<?php echo url(''); ?>/avatar/4043236_avatar_boy_male_portrait_icon.png" width="30" dalt="">
                                    @elseif($avatar == 4)
                                        <img src="<?php echo url(''); ?>/avatar/4043247_avatar_female_portrait_woman_icon.png" width="30" dalt="">
                                    @elseif($avatar == 5)
                                        <img src="<?php echo url(''); ?>/avatar/4043248_avatar_female_portrait_woman_icon.png" width="30" dalt="">
                                    @elseif($avatar == 6)
                                        <img src="<?php echo url(''); ?>/avatar/4043231_afro_female_person_woman_icon.png" width="30" dalt="">      
                                    @elseif($avatar == 7)
                                        <img src="<?php echo url(''); ?>/avatar/4043251_avatar_female_girl_woman_icon.png" width="30" dalt="">
                                    @elseif($avatar == 8)
                                        <img src="<?php echo url(''); ?>/avatar/4043277_avatar_person_pilot_traveller_icon.png" width="30" dalt="">                                                                       
                                    @elseif($avatar == 9)
                                        <img src="<?php echo url(''); ?>/avatar/4043270_avatar_joker_squad_suicide_woman_icon.png" width="30" dalt="">
                                    @elseif($avatar == 10)
                                        <img src="<?php echo url(''); ?>/avatar/2992459_girl_lady_user_woman_icon.png" width="30" dalt="">                                                                 
                                    @endif                                    
                                    &nbsp;{{ Auth::user()->name }}
                                </div>

                                <div class="ml-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link as="a" :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>

                <!-- Hamburger -->
                <div class="-mr-2 flex items-center sm:hidden">
                    <button @click="toggle" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path v-bind:class="{ hidden: toggled, 'inline-flex': !toggled }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path v-bind:class="{ hidden: !toggled, 'inline-flex': toggled }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Responsive Navigation Menu -->
        <div v-bind:class="{ block: toggled, hidden: !toggled }" class="sm:hidden">
            <div class="pt-2 pb-3 space-y-1">
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
            </div>

            <!-- My Tickets -->
            @if ($dev== '1' || $relator== '1')
                <div class="pt-2 pb-3 space-y-1">
                    <x-responsive-nav-link :href="route('tickets.mytickets')" :active="request()->routeIs('tckets.mytickets')">
                        {{ __('My Tickets') }}
                    </x-responsive-nav-link>
                </div>
            @endif

            <!-- Testing -->
            @if ($tester == '1')
                <div class="pt-2 pb-3 space-y-1">
                    <x-responsive-nav-link :href="route('tickets.testing')" :active="request()->routeIs('tckets.testing')">
                        {{ __('Testing') }}
                    </x-responsive-nav-link>
                </div>
            @endif

            <!-- New Ticket -->
            @if ($relator == '1')
                <div class="pt-2 pb-3 space-y-1">
                    <x-responsive-nav-link href="{{ route('tickets.show', base64_encode(0)) }}" :active="request()->routeIs('tickets.show')">
                        {{ __('New Ticket') }}
                    </x-responsive-nav-link>
                </div>
            @endif

            <div class="pt-2 pb-3 space-y-1">
                <x-responsive-nav-link href="{{ route('sprints.index', base64_encode(0)) }}" :active="request()->routeIs('sprints.index')">
                    {{ __('Sprint') }}
                </x-responsive-nav-link>
            </div>

            <div class="pt-2 pb-3 space-y-1">
                <x-responsive-nav-link href="{{ route('tickets.index') }}" :active="request()->routeIs('tickets.index')">
                    {{ __('Tickets') }}
                </x-responsive-nav-link>
            </div>

            <div class="pt-2 pb-3 space-y-1">
                <x-responsive-nav-link href="{{ route('documents.index') }}" :active="request()->routeIs('documents.index')">
                    {{ __('Documents') }}
                </x-responsive-nav-link>
            </div>

            <!-- Responsive Settings Options -->
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link as="a" :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        </div>
    </nav>
</x-splade-toggle>
