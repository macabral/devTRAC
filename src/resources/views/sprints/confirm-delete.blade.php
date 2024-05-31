<x-splade-modal>
    <x-splade-form method="delete" :action="route('sprints.destroy', $ret->id)" :default="$ret" class="mt-4 space-y-4" preserve-scroll>
        <p>{{ __("Do you confirm the deletion?") }}<br><b>{{ $ret->version }}</b></p>
        <div class="flex items-center gap-4">

            <x-splade-submit :label="__('Delete')" />
        
        </div>
    </x-splade-form>
</x-splade-modal>