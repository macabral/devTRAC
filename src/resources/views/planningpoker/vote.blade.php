<x-splade-modal>
    <p class="mt-1 text-xl text-gray-600">
        {{ __("Planning Poker Vote") }}
    </p>

    <x-splade-form method="post"  :action="route('planningpoker.save', [base64_encode($id)])"  class="mt-4 space-y-4" preserve-scroll>

        <x-splade-select id="storypoint" name="storypoint" :options="[0, 1, 2, 3, 5, 8, 13, 20, 40, 100]" required :label="__('Story Point')" />

        <div class="flex items-center gap-4">
        
            <x-splade-submit :label="__('Confirm')" />
        
        </div>

    </x-splade-form>


</x-splade-modal>