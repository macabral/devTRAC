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
                </x-splade-table>
            </section>
        </div> 
    </div>

</section>
