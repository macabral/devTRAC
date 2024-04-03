<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Configurations') }}
            </h2>
        </div>
    </x-slot>
    <div class="py-12">
        <div class=" mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div dusk="update-profile-information">
                    <section>
                        <p>{{ __('Story points in Scrum are units of measurement used to estimate the effort required to complete a story. When planning an upcoming sprint, Scrum teams use story point estimation to assess how much effort is needed to develop a new feature or software update. Report the average story points based on previous estimates.') }}</p>
                    </section>
                    <section>
                        <div  class="max-w-lg">
                        <x-splade-modal>
                            <x-splade-form method="patch" :action="route('config.update')" :default="$ret" class="mt-4 space-y-4" preserve-scroll>
                                @include('config.form')
                            </x-splade-form>
                        </x-splade-modal>
                    </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>