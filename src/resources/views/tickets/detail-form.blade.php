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

    @if (count($logs) != 0)
    <div class="py-2">
        <div class="mx-auto sm:px-6 lg:px-8">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="w-xl">
                    @include('tickets.detail.detail-log')
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="py-2">
        <div class="mx-auto sm:px-6 lg:px-8">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="w-xl">
                    @include('tickets.detail.detail-test-condition')
                </div>
            </div>
        </div>
    </div>

    @if ($ret->status == 'Open' || $ret->status == 'Testing')
    <div class="py-2">
        <div class="mx-auto sm:px-6 lg:px-8">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="w-xl">
                    @include('tickets.detail.detail-form')
                </div>
            </div>
        </div>
    </div>
    @endif

</x-app-layout>
