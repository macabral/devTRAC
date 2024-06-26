<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Projects') }}
        </h2>
    </header>

    <div class="py-2">
        <div class="sm:px-6 lg:px-12 space-y-6">
            <section>
                <x-splade-table :for="$ret" striped>
                    @cell('pivot.gp',$ret)
                        @if($ret->pivot->gp == 1)
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect width="24" height="24" fill="white"/>
                            <path d="M5 13.3636L8.03559 16.3204C8.42388 16.6986 9.04279 16.6986 9.43108 16.3204L19 7" stroke="#000000" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        @endif
                    @endcell
                    @cell('pivot.relator',$ret)
                        @if($ret->pivot->relator == 1)
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect width="24" height="24" fill="white"/>
                            <path d="M5 13.3636L8.03559 16.3204C8.42388 16.6986 9.04279 16.6986 9.43108 16.3204L19 7" stroke="#000000" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        @endif
                    @endcell
                    @cell('pivot.dev',$ret)
                        @if($ret->pivot->dev == 1)
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect width="24" height="24" fill="white"/>
                            <path d="M5 13.3636L8.03559 16.3204C8.42388 16.6986 9.04279 16.6986 9.43108 16.3204L19 7" stroke="#000000" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        @endif
                    @endcell
                    @cell('pivot.tester',$ret)
                        @if($ret->pivot->tester == 1)
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect width="24" height="24" fill="white"/>
                            <path d="M5 13.3636L8.03559 16.3204C8.42388 16.6986 9.04279 16.6986 9.43108 16.3204L19 7" stroke="#000000" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        @endif
                    @endcell   
                </x-splade-table>
            </section>
        </div> 
    </div>

</section>
