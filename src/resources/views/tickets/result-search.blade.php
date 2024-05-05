<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tickets') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-1xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <section>
                <x-splade-table :for="$ret" striped>
                    @cell('start', $ret)
                        @if ($ret->start == 1)
                            <img src="assets/start.png" width="10" height="10" dalt="">
                        @endif
                    @endcell
                    @cell('title', $ret)
                        <div>
                            <Link  href="{{ route('tickets.edit', base64_encode($ret->id)) }}" title="{{ __('Detail') }}">
                                <div class="mt-1 pb-2 pt-2 text-blue-800" style="width: 10%;">
                                    {!! nl2br(wordwrap($ret->title, 65,'<br>',true)) !!}
                                </div>
                            </Link>
                        </div>
                    @endcell
                    @cell('prioridade', $ret)
                        @if ($ret->prioridade == 'Crítica')
                            <div class="bg-red-500 rounded text-white">
                                &nbsp;{{ $ret->prioridade }}&nbsp;
                            </div>
                        @endif
                        @if ($ret->prioridade == 'Importante')
                            <div class="bg-indigo-500 rounded text-white">
                                &nbsp;{{ $ret->prioridade }}&nbsp;
                            </div>
                        @endif
                        @if ($ret->prioridade == 'Desejada')
                        <div class="rounded text-black">
                            &nbsp;{{ $ret->prioridade }}&nbsp;
                        </div>
                        @endif
                        @if ($ret->prioridade == 'Pode Esperar')
                        <div class="rounded text-black">
                            &nbsp;{{ $ret->prioridade }}&nbsp;
                        </div>
                        @endif
                    @endcell
                    @cell('action', $ret)
                        <div class="w-100 inline-flex">
                            @if ((Session::get('ret')[0]['relator'] == '1'  && $ret->user_id == auth('sanctum')->user()->id) || Session::get('ret')[0]['gp'] == '1')
                            <div class="pr-4">
                                <Link slideover href="{{ route('tickets.show', base64_encode($ret->id)) }}" title="{{ __('Edit') }}">
                                    <svg xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 50 50" width="16" height="16"><path d="M 43.125 2 C 41.878906 2 40.636719 2.488281 39.6875 3.4375 L 38.875 4.25 L 45.75 11.125 C 45.746094 11.128906 46.5625 10.3125 46.5625 10.3125 C 48.464844 8.410156 48.460938 5.335938 46.5625 3.4375 C 45.609375 2.488281 44.371094 2 43.125 2 Z M 37.34375 6.03125 C 37.117188 6.0625 36.90625 6.175781 36.75 6.34375 L 4.3125 38.8125 C 4.183594 38.929688 4.085938 39.082031 4.03125 39.25 L 2.03125 46.75 C 1.941406 47.09375 2.042969 47.457031 2.292969 47.707031 C 2.542969 47.957031 2.90625 48.058594 3.25 47.96875 L 10.75 45.96875 C 10.917969 45.914063 11.070313 45.816406 11.1875 45.6875 L 43.65625 13.25 C 44.054688 12.863281 44.058594 12.226563 43.671875 11.828125 C 43.285156 11.429688 42.648438 11.425781 42.25 11.8125 L 9.96875 44.09375 L 5.90625 40.03125 L 38.1875 7.75 C 38.488281 7.460938 38.578125 7.011719 38.410156 6.628906 C 38.242188 6.246094 37.855469 6.007813 37.4375 6.03125 C 37.40625 6.03125 37.375 6.03125 37.34375 6.03125 Z"/></svg>
                                </Link>
                            </div>
                            @endif
                            <div class="pr-4">
                                <Link slideover href="{{ route('files.show', base64_encode($ret->id)) }}" title="{{ __('Files') . ' (' . $ret->docs . ')' }}">
                                    <span >
                                        @if ($ret->docs != 0)
                                            <svg xmlns="http://www.w3.org/2000/svg" height="16" viewBox="0 -960 960 960" width="16"><path d="M459.923-42Q352-42 277-117.055 202-192.109 202-300v-435q0-78.787 55.5-134.894Q313-926 391.85-926q78.849 0 135 56.106Q583-813.787 583-735v387q0 51-36 87t-87 36q-51 0-87-36t-36-87v-387h91v387q0 14.025 9.2 23.513Q446.4-315 460-315q13.6 0 22.8-9.487Q492-333.975 492-348v-387q0-42-29-71t-71-29q-42 0-71 29t-29 71v435q0 70 49 119t119 49q70 0 119-49t49-119v-435h90v435q0 107.891-75.077 182.945Q567.846-42 459.923-42Z"/></svg>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" height="16" viewBox="0 -960 960 960" width="16"><path d="M460-80q-91 0-155.5-62.5T240-296v-430q0-64 45.5-109T395-880q65 0 110 45t45 110v394q0 38-26 64.5T460-240q-38 0-64-28.5T370-336v-392h40v395q0 22 14.5 37.5T460-280q21 0 35.5-15t14.5-36v-395q0-48-33.5-81T395-840q-48 0-81.5 33T280-726v432q0 73 53 123.5T460-120q75 0 127.5-51T640-296v-432h40v431q0 91-64.5 154T460-80Z"/></svg>
                                        @endif
                                    </span>
                                </Link>
                            </div>
                            @if ((Session::get('ret')[0]['relator'] == '1' && $ret->user_id == auth('sanctum')->user()->id) || Session::get('ret')[0]['gp'] == '1')
                            <div class="pr-4">
                                <Link modal href="{{ route('tickets.delete', base64_encode($ret->id)) }}" title="{{ __('Delete') }}">
                                    <svg xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 24 24" width="16" height="16"><path d="M 10.806641 2 C 10.289641 2 9.7956875 2.2043125 9.4296875 2.5703125 L 9 3 L 4 3 A 1.0001 1.0001 0 1 0 4 5 L 20 5 A 1.0001 1.0001 0 1 0 20 3 L 15 3 L 14.570312 2.5703125 C 14.205312 2.2043125 13.710359 2 13.193359 2 L 10.806641 2 z M 4.3652344 7 L 5.8925781 20.263672 C 6.0245781 21.253672 6.877 22 7.875 22 L 16.123047 22 C 17.121047 22 17.974422 21.254859 18.107422 20.255859 L 19.634766 7 L 4.3652344 7 z"/></svg>
                                </Link>
                            </div>
                            @endif                          
                        </div>
                    @endcell
                </x-splade-table>
            </section>
        </div> 
    </div>
</x-app-layout>


