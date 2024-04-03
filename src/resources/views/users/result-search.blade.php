<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Users') }}
            </h2>
            @if (Session::get('ret')[0]['admin'] == '1')
                <x-splade-button>
                    <Link slideover href="{{ route('users.show', base64_encode(0)) }}" class="pc-4 py-2 bg-indigo-400 hover:bg-indigo-600 text-black rounded-md">
                        {{ __('New User') }}
                    </Link>
                </x-splade-button>
            @endif
        </div>
    </x-slot>
   
    <div class="py-12">
        <div class="max-w-1xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <section>
                <x-splade-table :for="$ret" striped>
                    @cell('admin', $ret)
                        @if($ret->admin)
                            {{ __('Yes') }}
                        @else
                            {{ __('No') }}
                        @endif
                    @endcell
                    @cell('active', $ret)
                        @if($ret->active)
                            {{ __('Yes') }}
                        @else
                            {{ __('No') }}
                        @endif
                    @endcell                    
                    @cell('action', $ret)
                        <div class="flex flex-row">
                            <div class="basis-1/2 text-center">
                                <Link slideover href="{{ route('users.show', base64_encode($ret->id)) }}" title="{{ __('Edit') }}">
                                    <svg xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 50 50" width="13px" height="13px"><path d="M 43.125 2 C 41.878906 2 40.636719 2.488281 39.6875 3.4375 L 38.875 4.25 L 45.75 11.125 C 45.746094 11.128906 46.5625 10.3125 46.5625 10.3125 C 48.464844 8.410156 48.460938 5.335938 46.5625 3.4375 C 45.609375 2.488281 44.371094 2 43.125 2 Z M 37.34375 6.03125 C 37.117188 6.0625 36.90625 6.175781 36.75 6.34375 L 4.3125 38.8125 C 4.183594 38.929688 4.085938 39.082031 4.03125 39.25 L 2.03125 46.75 C 1.941406 47.09375 2.042969 47.457031 2.292969 47.707031 C 2.542969 47.957031 2.90625 48.058594 3.25 47.96875 L 10.75 45.96875 C 10.917969 45.914063 11.070313 45.816406 11.1875 45.6875 L 43.65625 13.25 C 44.054688 12.863281 44.058594 12.226563 43.671875 11.828125 C 43.285156 11.429688 42.648438 11.425781 42.25 11.8125 L 9.96875 44.09375 L 5.90625 40.03125 L 38.1875 7.75 C 38.488281 7.460938 38.578125 7.011719 38.410156 6.628906 C 38.242188 6.246094 37.855469 6.007813 37.4375 6.03125 C 37.40625 6.03125 37.375 6.03125 37.34375 6.03125 Z"/></svg>
                                </Link> 
                            </div>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <div class="basis-1/2 text-center">
                                <Link modal href="{{ route('users.projects', $ret->id) }}" title="{{ __('Associate Project') }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="16" viewBox="0 -960 960 960" width="16"><path d="M760-600q-57 0-99-34t-56-86H354q-11 42-41.5 72.5T240-606v251q52 14 86 56t34 99q0 66-47 113T200-40q-66 0-113-47T40-200q0-57 34-99t86-56v-251q-52-14-86-56t-34-98q0-66 47-113t113-47q56 0 98 34t56 86h251q14-52 56-86t99-34q66 0 113 47t47 113q0 66-47 113t-113 47Zm0 560q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113T760-40Zm0-640q33 0 56.5-23.5T840-760q0-33-23.5-56.5T760-840q-33 0-56.5 23.5T680-760q0 33 23.5 56.5T760-680Zm-560 0q33 0 56.5-23.5T280-760q0-33-23.5-56.5T200-840q-32 0-56 23.5T120-760q0 33 24 56.5t56 23.5Zm560 560q33 0 56.5-24t23.5-56q0-33-23.5-56.5T760-280q-33 0-56.5 23.5T680-200q0 32 23.5 56t56.5 24Zm-560 0q33 0 56.5-24t23.5-56q0-33-23.5-56.5T200-280q-32 0-56 23.5T120-200q0 32 24 56t56 24Zm560-640Zm-560 0Zm560 560Zm-560 0Z"/></svg>
                                </Link> 
                            </div>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <div class="basis-1/2 text-center">
                                <Link modal href="{{ route('users.delete', base64_encode($ret->id)) }}" title="{{ __('Delete') }}">
                                    <svg xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 24 24" width="16px" height="16px"><path d="M 10.806641 2 C 10.289641 2 9.7956875 2.2043125 9.4296875 2.5703125 L 9 3 L 4 3 A 1.0001 1.0001 0 1 0 4 5 L 20 5 A 1.0001 1.0001 0 1 0 20 3 L 15 3 L 14.570312 2.5703125 C 14.205312 2.2043125 13.710359 2 13.193359 2 L 10.806641 2 z M 4.3652344 7 L 5.8925781 20.263672 C 6.0245781 21.253672 6.877 22 7.875 22 L 16.123047 22 C 17.121047 22 17.974422 21.254859 18.107422 20.255859 L 19.634766 7 L 4.3652344 7 z"/></svg>
                                </Link>
                            </div>                            
                        </div>
                    @endcell
                </x-splade-table>
            </section>
        </div> 
    </div>
</x-app-layout>