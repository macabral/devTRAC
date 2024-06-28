<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
 
	<div class="grid grid-cols-2">
		<div class="align-left">
			<x-splade-form name="form" method="post" :action="route('dashboard.project')" :default="$input" class="mt-4 sm:px-6 lg:px-8 grid grid-cols-3 md:grid-cols-3 gap-3" preserve-scroll>
			<div>
					<div>
					<x-splade-select id="projects_id" name="projects_id" :options="$proj" option-label="title" option-value="projects_id"  placeholder="Projeto" autofocus/>
					</div>
					<div  class="mt-2">
					<x-splade-select id="sprints_id" name="sprints_id" :options="$sprints" option-label="version" option-value="id" placeholder="Sprint" remote-url="`api/sprints-dashboard/${form.projects_id}`" /> 
					</div>
					<div  class="mt-2"> 
					<x-splade-submit :label="__('Select Project')" />
					</div>
			</div>
			</x-splade-form>
		</div>

		<div class="flex justify-end align-middle inline-block mr-6">

			<div class="ml-10 p-6 border-8 rounded-md inline-block mt-10 bg-white  text-center text-blue-800 font-bold">
				<Link  href="{{ route('tickets.index') }}">
					{{ __('Total of Tickets') }}
				</Link> 
				<br><br>
				{{ $total }}
			</div>

			<div class="ml-10 p-6 border-8 rounded-md inline-block mt-10 bg-white text-center text-blue-800 font-bold">
				<Link slideover href="{{ route('projects.users', base64_encode($projeto)) }}">
					{{ __('Project Team') }}
				</Link> 
				<br><br>
				{{ $totalEquipe }}
			</div>

			@if($sitelink != '' || $gitlink != '')
				<div class="flex align-middle inline-block ml-10 mt-10">
				
					@if($sitelink != '')
						<a  href="{{ $sitelink }}" target="_blank" title="Site link">
							<div class="flex justify-intens-center inline-block">
								<svg width="32px" height="32px" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 122.88 105.37" style="enable-background:new 0 0 122.88 105.37" xml:space="preserve"><g><path d="M72.81,86.58c-3.83,4.63-8.47,9.22-13.89,13.77c1.17-0.16,2.34-0.35,3.48-0.57c3.13-0.63,6.13-1.55,9.04-2.81 c1.48-0.64,2.92-1.33,4.3-2.08l0.11,0.32c0.53,1.51,1.23,2.76,2.06,3.76c-1.52,0.82-3.08,1.58-4.68,2.26 c-3.22,1.39-6.54,2.4-9.96,3.1c-3.41,0.7-6.95,1.04-10.59,1.04s-7.14-0.35-10.59-1.04c-3.38-0.66-6.64-1.67-9.83-3.03 c-0.03,0-0.09-0.03-0.13-0.06c-3.16-1.36-6.16-3-8.98-4.87c-2.81-1.86-5.4-4.01-7.78-6.38c-2.4-2.37-4.52-4.96-6.38-7.78 c-1.9-2.81-3.51-5.82-4.87-8.98c-1.39-3.22-2.4-6.54-3.1-9.96C0.35,59.86,0,56.32,0,52.69c0-3.63,0.35-7.14,1.04-10.59 c0.66-3.38,1.68-6.64,3.03-9.83c0-0.03,0.03-0.09,0.06-0.13c1.36-3.19,3-6.16,4.87-8.98c1.86-2.81,4.01-5.4,6.38-7.78 c2.37-2.4,4.96-4.52,7.78-6.38c2.81-1.9,5.82-3.51,8.98-4.87c3.22-1.39,6.54-2.4,9.96-3.1C45.51,0.35,49.05,0,52.69,0 s7.14,0.35,10.59,1.04c3.38,0.66,6.64,1.67,9.83,3.03c0.03,0,0.09,0.03,0.13,0.06c3.16,1.36,6.16,3,8.98,4.87 c2.81,1.86,5.4,4.01,7.78,6.38c2.4,2.37,4.52,4.96,6.38,7.78c1.9,2.81,3.51,5.82,4.87,8.98c1.39,3.22,2.4,6.54,3.1,9.96 c0.04,0.2,0.08,0.4,0.12,0.6l-5.11-1.8c-0.59-2.37-1.36-4.67-2.34-6.92c-0.79-1.86-1.68-3.63-2.65-5.34l0,0H78.92 c1.21,2.07,2.27,4.13,3.18,6.19l-6.2-2.18c-0.73-1.33-1.52-2.67-2.38-4.01h-9.03c-1.06-0.33-2.2-0.41-3.31-0.24 c-0.35,0.06-0.7,0.14-1.05,0.24h-5.14v21.74h5.07l1.62,4.61h-6.69v21.74h14.35l1.62,4.61H54.99V97.6 C61.5,92.26,66.84,86.86,71,81.46L72.81,86.58L72.81,86.58z M112.3,92.8c-0.77,0.69-1.96,0.7-2.66,0L95.66,78.9l-6.63,13.19 c-2,3.98-5.56,5.78-7.24,1.02L61.68,36c-0.41-1.17,0.08-1.64,1.24-1.23l57.1,20.1c4.76,1.68,2.95,5.24-1.02,7.24l-13.19,6.63 l13.9,13.97c0.69,0.7,0.69,1.9,0,2.66L112.3,92.8L112.3,92.8L112.3,92.8z M46.49,100.35c-7.46-6.26-13.43-12.58-17.86-18.99H14.06 c1.39,1.9,2.94,3.67,4.61,5.34c2.18,2.18,4.52,4.11,7.08,5.82c2.53,1.71,5.28,3.19,8.25,4.46C34.04,97,34.07,97,34.1,97.03 c2.88,1.2,5.85,2.15,8.94,2.75c1.14,0.22,2.31,0.41,3.48,0.57H46.49L46.49,100.35z M11.03,76.74H25.7 c-4.08-7.14-6.29-14.41-6.54-21.74H4.68c0.13,2.53,0.41,4.96,0.89,7.36c0.63,3.13,1.55,6.13,2.81,9.04 C9.17,73.26,10.05,75.03,11.03,76.74L11.03,76.74z M4.68,50.38h14.57c0.54-7.21,2.94-14.44,7.21-21.74H11.03 c-0.98,1.71-1.86,3.48-2.65,5.34c-0.03,0.03-0.03,0.06-0.06,0.09c-1.2,2.88-2.15,5.85-2.75,8.94C5.09,45.42,4.77,47.85,4.68,50.38 L4.68,50.38z M14.03,24.02h15.39C33.82,17.7,39.6,11.35,46.81,4.96c-1.3,0.16-2.56,0.35-3.79,0.6c-3.13,0.63-6.13,1.55-9.04,2.81 c-2.94,1.26-5.69,2.75-8.25,4.46c-2.56,1.71-4.9,3.63-7.08,5.82c-1.67,1.68-3.22,3.44-4.61,5.34V24.02L14.03,24.02z M58.57,4.96 c7.24,6.38,13.02,12.74,17.38,19.06h15.39c-1.39-1.9-2.94-3.67-4.61-5.34c-2.18-2.18-4.52-4.11-7.08-5.82 c-2.53-1.71-5.28-3.19-8.25-4.46c-0.03-0.03-0.06-0.03-0.09-0.06c-2.88-1.2-5.85-2.15-8.94-2.75c-1.26-0.25-2.53-0.44-3.79-0.6 V4.96L58.57,4.96z M54.99,7.96v16.06h15.3C66.28,18.71,61.19,13.37,54.99,7.96L54.99,7.96z M50.38,97.6V81.35H34.29 C38.43,86.79,43.81,92.23,50.38,97.6L50.38,97.6z M50.38,76.74V54.99H23.77c0.28,7.3,2.75,14.54,7.3,21.74H50.38L50.38,76.74z M50.38,50.38V28.64H31.86c-4.74,7.36-7.4,14.6-8,21.74H50.38L50.38,50.38z M50.38,24.02V7.96c-6.19,5.4-11.28,10.75-15.3,16.06 H50.38L50.38,24.02z"/></g></svg>
							</div>
						</a>
						&nbsp;&nbsp;
					@endif
					@if($gitlink != '')
					<a href="{{ $gitlink }}" target="_blank" title="Git link">
						<div class="flex justify-center inline-block">
							<svg width="32px" height="32px" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M6.79286 1.20708L7.14642 1.56063L7.14642 1.56063L6.79286 1.20708ZM1.20708 6.79287L0.853524 6.43931H0.853524L1.20708 6.79287ZM1.20708 8.20708L1.56063 7.85352L1.56063 7.85352L1.20708 8.20708ZM6.79287 13.7929L6.43931 14.1464L6.79287 13.7929ZM8.20708 13.7929L7.85352 13.4393L8.20708 13.7929ZM13.7929 8.20708L14.1464 8.56063L13.7929 8.20708ZM13.7929 6.79286L13.4393 7.14642L13.7929 6.79286ZM8.20708 1.20708L8.56063 0.853524V0.853524L8.20708 1.20708ZM6.43931 0.853525L0.853524 6.43931L1.56063 7.14642L7.14642 1.56063L6.43931 0.853525ZM0.853525 8.56063L6.43931 14.1464L7.14642 13.4393L1.56063 7.85352L0.853525 8.56063ZM8.56063 14.1464L14.1464 8.56063L13.4393 7.85352L7.85352 13.4393L8.56063 14.1464ZM14.1464 6.43931L8.56063 0.853524L7.85352 1.56063L13.4393 7.14642L14.1464 6.43931ZM14.1464 8.56063C14.7322 7.97484 14.7322 7.0251 14.1464 6.43931L13.4393 7.14642C13.6346 7.34168 13.6346 7.65826 13.4393 7.85352L14.1464 8.56063ZM6.43931 14.1464C7.0251 14.7322 7.97485 14.7322 8.56063 14.1464L7.85352 13.4393C7.65826 13.6346 7.34168 13.6346 7.14642 13.4393L6.43931 14.1464ZM0.853524 6.43931C0.267737 7.0251 0.267739 7.97485 0.853525 8.56063L1.56063 7.85352C1.36537 7.65826 1.36537 7.34168 1.56063 7.14642L0.853524 6.43931ZM7.14642 1.56063C7.34168 1.36537 7.65826 1.36537 7.85352 1.56063L8.56063 0.853524C7.97484 0.267737 7.0251 0.267739 6.43931 0.853525L7.14642 1.56063ZM5.14642 2.85352L6.14642 3.85352L6.85352 3.14642L5.85352 2.14642L5.14642 2.85352ZM7.49997 4.99997C7.22383 4.99997 6.99997 4.77611 6.99997 4.49997H5.99997C5.99997 5.3284 6.67154 5.99997 7.49997 5.99997V4.99997ZM7.99997 4.49997C7.99997 4.77611 7.77611 4.99997 7.49997 4.99997V5.99997C8.3284 5.99997 8.99997 5.3284 8.99997 4.49997H7.99997ZM7.49997 3.99997C7.77611 3.99997 7.99997 4.22383 7.99997 4.49997H8.99997C8.99997 3.67154 8.3284 2.99997 7.49997 2.99997V3.99997ZM7.49997 2.99997C6.67154 2.99997 5.99997 3.67154 5.99997 4.49997H6.99997C6.99997 4.22383 7.22383 3.99997 7.49997 3.99997V2.99997ZM8.14642 5.85352L9.64642 7.35352L10.3535 6.64642L8.85352 5.14642L8.14642 5.85352ZM10.5 7.99997C10.2238 7.99997 9.99997 7.77611 9.99997 7.49997H8.99997C8.99997 8.3284 9.67154 8.99997 10.5 8.99997V7.99997ZM11 7.49997C11 7.77611 10.7761 7.99997 10.5 7.99997V8.99997C11.3284 8.99997 12 8.3284 12 7.49997H11ZM10.5 6.99997C10.7761 6.99997 11 7.22383 11 7.49997H12C12 6.67154 11.3284 5.99997 10.5 5.99997V6.99997ZM10.5 5.99997C9.67154 5.99997 8.99997 6.67154 8.99997 7.49997H9.99997C9.99997 7.22383 10.2238 6.99997 10.5 6.99997V5.99997ZM6.99997 5.49997V9.49997H7.99997V5.49997H6.99997ZM7.49997 11C7.22383 11 6.99997 10.7761 6.99997 10.5H5.99997C5.99997 11.3284 6.67154 12 7.49997 12V11ZM7.99997 10.5C7.99997 10.7761 7.77611 11 7.49997 11V12C8.3284 12 8.99997 11.3284 8.99997 10.5H7.99997ZM7.49997 9.99997C7.77611 9.99997 7.99997 10.2238 7.99997 10.5H8.99997C8.99997 9.67154 8.3284 8.99997 7.49997 8.99997V9.99997ZM7.49997 8.99997C6.67154 8.99997 5.99997 9.67154 5.99997 10.5H6.99997C6.99997 10.2238 7.22383 9.99997 7.49997 9.99997V8.99997Z" fill="#000000"/>
							</svg>
						</div>
					</a> 
					@endif
				</div>
			@endif

		</div>
	</div>

    <div class="flex flex-wrap mt-6 sm:px-6 lg:px-8 mx-6 mr-6 grid grid-cols-2 md:grid-cols-2 gap-6 bg-white">

    @if (count($stats) > 0)
      <div>
        <div>
			<div>
				<br>
				<div id="chart1" name="chart1"></div>
			  </div>
        <table class="min-w-full bg-white mt-6 text-left text-sm font-light rounded">
            <thead class="border-b font-medium dark:border-neutral-500">
                <tr>
                  <th class="text-left">{{ __('Project') }}</th>
                  <th class="text-left">Sprint</th>
                  <th class="text-left">{{ __('Start') }}</th>
                  <th class="text-left">{{ __('End') }}</th>
                  <th class="text-left">{{ __('Type') }}</th>
                  <th class="text-center">Story Points</th>
				  <th class="text-center">{{ __('Function Points') }}</th>
				  <th class="text-center">Total</th>
                  <th class="text-center">Open</th>
                  <th class="text-center">Testing</th>
                  <th class="text-center">Closed</th>

                </tr>
              </thead>
              <tbody>
                    @php
                        $totalOpen = 0; $totalClosed = 0; $totalTesting = 0; $totalStory = 0; $totalpf = 0;
                    @endphp
                    @foreach($stats as $item)
                    @php
                        $totalOpen +=  $item['open'];
                        $totalClosed += $item['closed'];
                        $totalTesting += $item['testing'];
                        $totalStory += $item['storypoint'];
						$totalpf += $item['pf'];
                    @endphp
                    <tr class="border-b dark:border-neutral-500">
                        <td>{{ $item['project'] }}</td>
                        <td>{{ $item['sprint'] }}</td>
                        <td>{{ date('d/m/Y', strtotime($item['start'])) }}</td>
                        <td>{{ date('d/m/Y', strtotime($item['end'])) }}</td>
                        <td>{{ $item['type'] }}</td>
                        <td class="text-center">{{ $item['storypoint'] }}</td>
						<td class="text-center">{{ $item['pf'] }}</td>
						<td class="text-center">{{ $item['open'] + $item['closed'] + $item['testing'] }}</td>
                        <td class="text-center">{{ $item['open'] }}</td>
                        <td class="text-center">{{ $item['testing'] }}</td>
                        <td class="text-center">{{ $item['closed'] }}</td>
                    </tr>
                    @endforeach
                    <tr class="border-b dark:border-neutral-500">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="text-center">{{ $totalStory }}</td>
						<td class="text-center">{{ $totalpf }}</td>
						<td class="text-center">{{ $totalOpen + $totalClosed + $totalTesting }}</td>
                        <td class="text-center">{{ $totalOpen }}</td>
                        <td class="text-center">{{ $totalTesting }}</td>
                        <td class="text-center">{{ $totalClosed }}</td>
                        
                    </tr>
              </tbody>
        </table>
        </div>

		@if (count($perdev) > 0)
		<div>
		   <table class="min-w-full bg-white mt-6 text-left text-sm font-light rounded">
			   <thead class="border-b font-medium dark:border-neutral-500">
				   <tr>
					 <th class="text-left">Dev</th>
					 <th class="text-left">{{ __('Project') }}</th>
					 <th class="text-left">Sprint</th>
					 <th class="text-center">Story Points</th>
					 <th class="text-center">Total</th>
					 <th class="text-center">Open</th>
					 <th class="text-center">Testing</th>
					 <th class="text-center">Closed</th>
				   </tr>
				 </thead>
				 <tbody>
					   @foreach($perdev as $item)
					   <tr class="border-b dark:border-neutral-500">
						 <td>{{ $item['name'] }}</td>
						 <td>{{ $item['project'] }}</td>
						 <td>{{ $item['sprint'] }}</td>
						 <td class="text-center">{{ $item['storypoint'] }}</td>
						 <td class="text-center">{{ $item['open'] + $item['closed'] + $item['testing'] }}</td>
						 <td class="text-center">{{ $item['open'] }}</td>
						 <td class="text-center">{{ $item['testing'] }}</td>
						 <td class="text-center">{{ $item['closed'] }}</td>
					   </tr>
					   @endforeach
				 </tbody>
		   </table>
	   </div>
	   @endif


      </div>
    @else
    <div class="rounded">
		<br>
        <p>Você não possui tarefas no momento.</p>
		<br>
    </div>
  @endif

  
  <div class="rounded bg-white mt-6">

    <div id="chart2" name="chart2"></div>

	<div id="chart3" name="chart3"></div>

	@if ($pf_medio != 0)
		<div id="chart4" name="chart4"></div>
	@endif
	
	<br><br>

</div>

</div>
<br><br>
    <x-splade-script>

        <!-- Sprint Burndown -->

        @if (! is_null($chart1))

			var cat = "{{ $chart1['categories'] }}"
			var categories = cat.split(',')
			var data1 = {{ $chart1['data1'] }}
			var data2 = {{ $chart1['data2'] }}
			var title = "{{ $chart1['title'] }}"

			var options = {
				series: [
				{
					name: "Estimado",
					data: data1
				},
				{
					name: "Real",
					data:  data2
				}
				],
				chart: {
				height: '200%',
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
	
			var chart1 = new ApexCharts(document.querySelector("#chart1"), options);
			chart1.render();

		@endif

		<!-- Sprint -->

		@if (! is_null($chart2))

			var cat2 = "{{ $chart2['categories']  }}"
			var categ2 = cat2.split(',')
			var data1 = {{ $chart2['data1'] }}
			var data2 = {{ $chart2['data2'] }}
			var data3 = {{ $chart2['data3'] }}
			var title = "{{ $chart2['title'] }}"

			var ar = []
			categ2.forEach((elem) => {
				ar.push(elem)

			})
					
			var options = {
				series: [
				{
					name: 'Melhoria',
					data: data1
				}, {
					name: 'Defeito',
					data: data2
				}, {
					name: 'Suporte Técnico',
					data: data3
				}],
				chart: {
					type: 'bar',
					height: 350,
					stacked: true,
					toolbar: {
					show: true
				},
				zoom: {
					enabled: true
				}
			},
			responsive: [{
				breakpoint: 480,
				options: {
				legend: {
					position: 'bottom',
					offsetX: -10,
					offsetY: 0
				}
				}
			}],
			plotOptions: {
				bar: {
				horizontal: false,
				borderRadius: 10,
				dataLabels: {
					total: {
					enabled: true,
					style: {
						fontSize: '13px',
						fontWeight: 900
					}
					}
				}
				},
			},
			xaxis: {
				type: 'text',
				categories: ar,
			},
			legend: {
				position: 'right',
				offsetY: 40
			},
			fill: {
				opacity: 1
			},
			title: {
				text: title,
				align: 'left'
			},
			};
	
			var chart2 = new ApexCharts(document.querySelector("#chart2"), options);
			chart2.render();
		
		@endif

        <!-- Sprint/Story Points -->
		@if (! is_null($chart3))
			var cat2 = "{{ $chart3['categories']  }}"
			var categ2 = cat2.split(',')
			var data1 = {{ $chart3['data1'] }}
			var title = "{{ $chart3['title'] }}"

			var ar = []
			categ2.forEach((elem) => {
				ar.push(elem)

			})

			var options = {
				series: [{
				name: title,
				data: data1
			}],
				chart: {
				type: 'bar',
				height: 350,
				stacked: true,
				toolbar: {
				show: true
				},
				zoom: {
				enabled: true
				}
			},
			responsive: [{
				breakpoint: 480,
				options: {
				legend: {
					position: 'bottom',
					offsetX: -10,
					offsetY: 0
				}
				}
			}],
			plotOptions: {
				bar: {
				horizontal: false,
				borderRadius: 10,
				dataLabels: {
					total: {
					enabled: false,
					style: {
						fontSize: '13px',
						fontWeight: 900
					}
					}
				}
				},
			},
			xaxis: {
				type: 'text',
				categories: ar,
			},
			fill: {
				opacity: 1
			},
			title: {
				text: title + ' (Média de  {{ $storypoint_medio }})',
				align: 'left'
			},
			};
	
			var chart3 = new ApexCharts(document.querySelector("#chart3"), options);
			chart3.render();

        @endif

		<!-- Sprint/PF-->
		@if (! count($chart4) == 0)
			var cat2 = "{{ $chart4['categories']  }}"
			var categ2 = cat2.split(',')
			var data1 = {{ $chart4['data1'] }}
			var title = "{{ $chart4['title'] }}"

			var ar = []
			categ2.forEach((elem) => {
				ar.push(elem)
			})

			var options = {
				series: [{
				name: title,
				data: data1
			}],
				chart: {
					type: 'bar',
					height: 350,
					stacked: true,
					toolbar: {
						show: true
					},
					zoom: {
						enabled: true
					}
			},
			responsive: [{
				breakpoint: 480,
				options: {
				legend: {
					position: 'bottom',
					offsetX: -10,
					offsetY: 0
				}
				}
			}],
			plotOptions: {
				bar: {
				horizontal: false,
				borderRadius: 10,
				dataLabels: {
					total: {
						enabled: false,
						style: {
							fontSize: '13px',
							fontWeight: 900
						}
					}
				}
				},
			},
			xaxis: {
				type: 'text',
				categories: ar,
			},
			fill: {
				opacity: 1
			},
			title: {
				text: title + ' (Média de  {{ $pf_medio }})',
				align: 'left'
			},
			};

			var chart4 = new ApexCharts(document.querySelector("#chart4"), options);
			chart4.render();
		@endif

    </x-splade-script>
</x-app-layout>

