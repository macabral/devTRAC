<x-app-layout>
    <x-slot name="header">
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl" dusk="update-profile-information">
                    <section>
                        <header>
                            <p class="mt-1 text-sm text-gray-600">
                                {{ __("Log") }}
                            </p>
                        </header>
                    
                        <x-splade-modal max-width="3xl">

                            @if ($userId == auth('sanctum')->user()->id && $ret->status != 'Closed')

                                <x-splade-form method="post" :action="route('logtickets.save',  [$ret->id, $origin])" :default="['description' => $description ]"  class="mt-4 space-y-4" preserve-scroll novalidate>

                                    <x-splade-wysiwyg id="description" name="description" autosize rows="7" :label="__('Add Comments')" required autocomplete="description" />
                                
                                    <x-splade-submit :label="__('Save Comments')" :spinner="true" />

                                </x-splade-form>

                            @endif
                            
                            <div class="pt-1 pb-1">
                                @foreach ($logs as $item)
                            
                                    <div class="pt-2 text-sm text-gray-500">
                                        <div class="w-100 inline-flex">
                                            {{ $item->id }}: {{ date('d/m/Y H:i', strtotime($item->created_at)) }}, {{ $item->name }}&nbsp;&nbsp;
                                        </div>
                                        <hr>
                                        <div class="text-sm text-gray-900" style="line-height:1.3; white-space:pre-wrap; padding: 1em;">
                                            {!! (nl2br($item->description)) !!}
                                        </div>
                            
                                    </div>
                                        
                                @endforeach
    
                            </div>

                        </x-splade-modal>

                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>