<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('New Ticket') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-1xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl" dusk="update-profile-information">
                    <section>
                        <header>
                            <p class="mt-1 text-sm text-gray-600">
                                {{ __("") }}
                            </p>
                        </header>
                    
                        <x-splade-modal>
                            <x-splade-form method="post" :action="route('tickets.create')" :default="$ret" class="mt-4 space-y-4" preserve-scroll>
                                @include('tickets.fields-form')
                            </x-splade-form>
                        </x-splade-modal>
                        
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>