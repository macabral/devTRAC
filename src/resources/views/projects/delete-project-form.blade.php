<x-splade-modal :default="$proj">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Delete Project?') }}
        

        <p class="mt-1 text-sm text-gray-600">
            {{ $proj->title }}
        </p>

        </h2>

    </header>

    <br><br>


    <x-splade-form
        method="delete"
        :default="$proj"
        :action="route('projects.destroy', $proj->id)"
        :confirm="__('Are you sure you want to delete?')"
        :confirm-text="__('Please enter your password to confirm you would like to delete.')"
        :confirm-button="__('Delete Project')"
        require-password
    >
        <x-splade-submit danger :label="__('Delete Project')" />
    </x-splade-form>
</x-splade-modal>