<div style="width: 50%;">
    <x-splade-textarea id="description" name="description" autosize rows="7" :label="__('Add Comments')" required autocomplete="description" />
</div>

<div class="inline-flex">
    <div class="flex items-center gap-4"> 

        <x-splade-submit :label="__('Save')" />

        @if ($ret->status == 'Open' && Session::get('ret')[0]['dev'] == '1')
        <div class="inline-flex gap-4">
            <x-splade-button>
                <Link slideover href="{{ route('logtickets.update', [$ret->id, 'Testing']) }}" class="pc-4 py-2 bg-indigo-400 hover:bg-indigo-600 text-black rounded-md">
                    {{ __('Submit for Testing') }}
                </Link>
            </x-splade-button>
        </div>
        @endif

        @if ($ret->status == 'Testing' && Session::get('ret')[0]['tester'] == '1')
            <div class="inline-flex gap-4">
                <div>
                    <x-splade-button>
                        <Link slideover href="{{ route('logtickets.update', [$ret->id, 'Closed']) }}" class="pc-4 py-2 bg-indigo-400 hover:bg-indigo-600 text-black rounded-md">
                            {{ __('Close Ticket') }}
                        </Link>
                    </x-splade-button>
                </div>
                <div>
                    <x-splade-button>
                        <Link slideover href="{{ route('logtickets.update', [$ret->id, 'Open']) }}" class="pc-4 py-2 bg-indigo-400 hover:bg-indigo-600 text-black rounded-md">
                            {{ __('Reopen Ticket') }}
                        </Link>
                    </x-splade-button>
                </div>
            </div>
        @endif



    </div>
</div>
