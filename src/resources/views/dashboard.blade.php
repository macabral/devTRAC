<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    @if (count($proj) > 1)
    <div class="flex py-12 flex-wrap max-w-1xl mx-auto sm:px-6 lg:px-8 grid grid-cols-2 md:grid-cols-4 gap-4">
        <x-splade-form method="post" :action="route('dashboard.project')" class="mt-4 space-y-4" preserve-scroll>
            <x-splade-group name="selProject" inline>
                @foreach($proj as $item)
                    <div class="h-auto max-w-full rounded-lg bg-gray-200 dark:bg-gray-700 px-6 py-2">
                            
                            <p class="font-bold text-xl mb-2">
                                <x-splade-radio name="selProject" value="{{ $item->id }}" label="{{ $item->title }}" />
                            </p>
                            <p class="text-gray-900 text-base">
                                {{ $item->description }}
                            </p>
                    </div>
                @endforeach
            </x-splade-group>
            <x-splade-submit :label="__('Select Project')" />
        </x-splade-form>
    </div>
    @endif

    @if (count($stats) > 0)
    <div class="flex py-2 flex-wrap max-w-1xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-2">
        <table class="min-w-full text-left text-sm font-light">
            <thead class="border-b font-medium dark:border-neutral-500">
                <tr>
                  <th class="text-left">Projeto</th>
                  <th class="text-left">Release</th>
                  <th class="text-left">Tipo</th>
                  <th class="text-center">Open</th>
                  <th class="text-center">Testing</th>
                  <th class="text-center">Closed</th>
                  <th class="text-center">Total</th>
                </tr>
              </thead>
              <tbody>
                    @foreach($stats as $item)
                    <tr class="border-b dark:border-neutral-500">
                        <td>{{ $item['project'] }}</td><td>{{ $item['release'] }}</td><td>{{ $item['type'] }}</td><td class="text-center">{{ $item['open'] }}</td><td class="text-center">{{ $item['testing'] }}</td><td class="text-center">{{ $item['closed'] }}</td><td class="text-center">{{ $item['open'] + $item['closed'] + $item['testing'] }}</td>
                    </tr>
                    @endforeach
              </tbody>
        </table>
    </div>
    @else
    <div class="flex py-2 flex-wrap max-w-1xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-2">
        <p>Você não possui tarefas no momento.</p>
    </div>
    @endif

    @if (count($perdev) > 0)
     <div class="flex py-2 flex-wrap max-w-1xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-2">
        <table class="min-w-full text-left text-sm font-light">
            <thead class="border-b font-medium dark:border-neutral-500">
                <tr>
                  <th class="text-left">Projeto</th>
                  <th class="text-left">Release</th>
                  <th class="text-left">Tipo</th>
                  <th class="text-left">Dev</th>
                  <th class="text-center">Open</th>
                  <th class="text-center">Testing</th>
                  <th class="text-center">Closed</th>
                  <th class="text-center">Total</th>
                </tr>
              </thead>
              <tbody>
                    @foreach($perdev as $item)
                    <tr class="border-b dark:border-neutral-500">
                        <td>{{ $item['project'] }}</td><td>{{ $item['release'] }}</td><td>{{ $item['type'] }}</td><td>{{ $item['name'] }}</td><td class="text-center">{{ $item['open'] }}</td><td class="text-center">{{ $item['testing'] }}</td><td class="text-center">{{ $item['closed'] }}</td><td class="text-center">{{ $item['open'] + $item['closed'] + $item['testing'] }}</td>
                    </tr>
                    @endforeach
              </tbody>
        </table>
    </div>
    @endif

</x-app-layout>
