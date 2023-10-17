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
                  <th class="text-left">Sprint</th>
                  <th class="text-left">Início</th>
                  <th class="text-left">Fim</th>
                  <th class="text-left">Tipo</th>
                  <th class="text-center">Open</th>
                  <th class="text-center">Testing</th>
                  <th class="text-center">Closed</th>
                  <th class="text-center">Total</th>
                </tr>
              </thead>
              <tbody>
                    @php
                        $totalOpen = 0; $totalClosed = 0; $totalTesting = 0;
                    @endphp
                    @foreach($stats as $item)
                    @php
                        $totalOpen +=  $item['open'];
                        $totalClosed += $item['closed'];
                        $totalTesting += $item['testing'];
                    @endphp
                    <tr class="border-b dark:border-neutral-500">
                        <td>{{ $item['project'] }}</td><td>{{ $item['release'] }}</td><td>{{ date('d/m/Y', strtotime($item['start'])) }}</td><td>{{ date('d/m/Y', strtotime($item['end'])) }}</td><td>{{ $item['type'] }}</td><td class="text-center">{{ $item['open'] }}</td><td class="text-center">{{ $item['testing'] }}</td><td class="text-center">{{ $item['closed'] }}</td><td class="text-center">{{ $item['open'] + $item['closed'] + $item['testing'] }}</td>
                    </tr>
                    @endforeach
                    <tr class="border-b dark:border-neutral-500">
                        <td></td><td></td><td></td><td></td><td></td><td class="text-center">{{ $totalOpen }}</td><td class="text-center">{{ $totalTesting }}</td><td class="text-center">{{ $totalClosed }}</td><td class="text-center">{{ $totalOpen + $totalClosed + $totalTesting }}</td>
                    </tr>
              </tbody>
        </table>
    </div>
    @else
    <div class="flex py-2 flex-wrap max-w-1xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-2">
        <p>Você não possui tarefas no momento.</p>
    </div>
    @endif

    <div class="flex py-4 flex-wrap max-w-1xl mx-auto sm:px-6 lg:px-7 grid grid-cols-1 md:grid-cols-2">


        <div id="chart" name="chart"></div>


    </div>


    @if (count($perdev) > 0)
     <div class="flex py-2 flex-wrap max-w-1xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-2">
        <table class="min-w-full text-left text-sm font-light">
            <thead class="border-b font-medium dark:border-neutral-500">
                <tr>
                  <th class="text-left">Projeto</th>
                  <th class="text-left">Sprint</th>
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
    <x-splade-script>

        var cat = "{{ $chart['categories'] }}"
        var data1 = {{ $chart['data1'] }}
        var data2 = {{ $chart['data2'] }}
        var title = "{{ $chart['title'] }}"

        var categories = cat.split(',')

        var options = {
            series: [{
              name: "Estimado",
              data: data1
            },
            {
            name: "Real",
            data:  data2
            }],
            chart: {
            height: 350,
            width:'100%',
            type: 'line',
            zoom: {
              enabled: false
            }
          },
          dataLabels: {
            enabled: false
          },
          stroke: {
            curve: 'straight'
          },
          title: {
            text: title,
            align: 'left'
          },
          grid: {
            row: {
              colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
              opacity: 0.5
            },
          },
          xaxis: {
            categories: categories,
          }
          };
  
          var chart = new ApexCharts(document.querySelector("#chart"), options);
          chart.render();
    </x-splade-script>
</x-app-layout>
