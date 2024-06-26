<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ticket') }} #{{ $ret['id'] }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="mx-auto sm:px-6 lg:px-8">
            <div class="bg-white">
                <div class="w-xl">
                    @include('tickets.detail.detail-project')
                </div>
            </div>
        </div>
    </div>

    <div class="py-1">
        <div class="mx-auto sm:px-6 lg:px-8">
            <div class="p-4 sm:p-8 bg-white">
                <div class="w-xl">
                    @include('tickets.detail.detail-info')
                </div>
            </div>
        </div>
    </div>

    <div class="py-1">
        <div class="mx-auto sm:px-6 lg:px-8">
            <div class="p-4 sm:p-8 bg-white">
                <div class="w-xl">
                    @include('planningpoker.pp')
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
