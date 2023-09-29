<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('New Project') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl" dusk="update-profile-information">
                    <section>
                        <header>
                            <p class="mt-1 text-sm text-gray-600">
                                {{ __("") }}
                            </p>
                        </header>
                    
                        <x-splade-modal>
                            <x-splade-form method="post" :action="route('users.associate', $id)" :default="$ret" class="mt-4 space-y-4" preserve-scroll>
                                <x-splade-select id="projects_id" name="projects_id" :options="$ret" option-label="title" option-value="id" :label="__('Projects')" />
                                <x-splade-checkbox name="gp" value="1" false-value="0" :label="__('Project Manager')" />
                                <x-splade-checkbox name="relator" value="1" false-value="0" :label="__('Relator')" />
                                <x-splade-checkbox name="dev" value="1" false-value="0" :label="__('Developer')" />
                                <x-splade-checkbox name="tester" value="1" false-value="0" :label="__('Tester')" />
                                <div class="flex items-center gap-4">

                                    <x-splade-submit :label="__('Associate')" />

                                </div>
                            </x-splade-form>
                        </x-splade-modal>
                        
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>