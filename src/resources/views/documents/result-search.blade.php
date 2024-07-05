<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Documents') }}
            </h2>
            @if (Session::get('ret')[0]['gp'] == '1')
                <x-splade-button>
                    <Link slideover href="{{ route('documents.show', base64_encode(0)) }}" class="pc-4 py-2 text-white rounded-md">
                        {{ __('New Document') }}
                    </Link>
                </x-splade-button>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-1xl mx-auto sm:px-6 lg:px-8 space-y-4">

            <section>
                <x-splade-table :for="$ret" striped>
                    @cell('title', $ret)
                    <div>
                        <div class="mt-1 pb-2 pt-2 text-blue-800" style="width: 10%;">
                            <Link slideover href="{{ route('document-files.show', base64_encode($ret->id)), 'documents' }}" title="{{ __('Edit') }}">
                                {!! nl2br(wordwrap($ret->title, 150,'<br>',true)) !!}
                            </Link>
                        </div>
                    </div>
                    @endcell
                    @cell('action', $ret)
                        <div class="flex flex-row">
                            
                                <div class="basis-1/2 text-center">
                                    <Link slideover href="{{ route('documents.show', base64_encode($ret->id)) }}" title="{{ __('Edit') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 50 50" width="16" height="16"><path d="M 43.125 2 C 41.878906 2 40.636719 2.488281 39.6875 3.4375 L 38.875 4.25 L 45.75 11.125 C 45.746094 11.128906 46.5625 10.3125 46.5625 10.3125 C 48.464844 8.410156 48.460938 5.335938 46.5625 3.4375 C 45.609375 2.488281 44.371094 2 43.125 2 Z M 37.34375 6.03125 C 37.117188 6.0625 36.90625 6.175781 36.75 6.34375 L 4.3125 38.8125 C 4.183594 38.929688 4.085938 39.082031 4.03125 39.25 L 2.03125 46.75 C 1.941406 47.09375 2.042969 47.457031 2.292969 47.707031 C 2.542969 47.957031 2.90625 48.058594 3.25 47.96875 L 10.75 45.96875 C 10.917969 45.914063 11.070313 45.816406 11.1875 45.6875 L 43.65625 13.25 C 44.054688 12.863281 44.058594 12.226563 43.671875 11.828125 C 43.285156 11.429688 42.648438 11.425781 42.25 11.8125 L 9.96875 44.09375 L 5.90625 40.03125 L 38.1875 7.75 C 38.488281 7.460938 38.578125 7.011719 38.410156 6.628906 C 38.242188 6.246094 37.855469 6.007813 37.4375 6.03125 C 37.40625 6.03125 37.375 6.03125 37.34375 6.03125 Z"/></svg>
                                    </Link>
                                </div>
                                
                                &nbsp;&nbsp;&nbsp;&nbsp;

                                <div class="basis-1/2 text-center">
                                    <Link modal href="{{ route('documents.delete', base64_encode($ret->id)) }}" title="{{ __('Delete') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 24 24" width="16" height="'16'"><path d="M 10.806641 2 C 10.289641 2 9.7956875 2.2043125 9.4296875 2.5703125 L 9 3 L 4 3 A 1.0001 1.0001 0 1 0 4 5 L 20 5 A 1.0001 1.0001 0 1 0 20 3 L 15 3 L 14.570312 2.5703125 C 14.205312 2.2043125 13.710359 2 13.193359 2 L 10.806641 2 z M 4.3652344 7 L 5.8925781 20.263672 C 6.0245781 21.253672 6.877 22 7.875 22 L 16.123047 22 C 17.121047 22 17.974422 21.254859 18.107422 20.255859 L 19.634766 7 L 4.3652344 7 z"/></svg>
                                    </Link>
                                </div>

                        </div>
                    @endcell
                </x-splade-table>
            </section>
        </div> 
    </div>

</x-app-layout>
