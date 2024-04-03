 <x-splade-form method="post" :action="route('logtickets.create',  $ret->id)"  class="mt-4 space-y-4" preserve-scroll>

    <x-splade-textarea id="description" name="description" autosize rows="7" :label="__('Add Comments')" required autocomplete="description" />

    <x-splade-submit :label="__('Save Comments')" :spinner="true" />

    @if ($ret->status == 'Open' && Session::get('ret')[0]['dev'] == '1')
        <x-splade-form method="get" :action="route('logtickets.update',[$ret->id,'Testing'])"  class="mt-4 space-y-4" preserve-scroll
            confirm="Confirma enviar o tíquete para teste?"
            confirm-text=" "
            confirm-button="Confirmar"
            cancel-button="Cancelar"
        >
            <div class="inline-flex gap-4">
                <x-splade-submit :label="__('Submit for Testing')" secondary />
            </div>
        </x-splade-form>
    @endif

    @if ($ret->status == 'Testing' && Session::get('ret')[0]['tester'] == '1')
        <div class="inline-flex gap-4">
            <div>
                <x-splade-form method="get" :action="route('logtickets.update',[$ret->id, 'Closed'])"  class="mt-4 space-y-4" preserve-scroll
                    confirm="Confirma Fechar o tíquete?"
                    confirm-text=""
                    confirm-button="Confirmar"
                    cancel-button="Cancelar"
                >
                    <div class="inline-flex gap-4">
                        <x-splade-submit :label="__('Close Ticket')" secondary />
                    </div>
                </x-splade-form>
            </div>
            <div>
                <x-splade-form method="get" :action="route('logtickets.update',[$ret->id, 'Open'])"  class="mt-4 space-y-4" preserve-scroll
                    confirm="Confirma Reabrir o tíquete?"
                    confirm-text=""
                    confirm-button="Confirmar"
                    cancel-button="Cancelar"
                >
                    <div class="inline-flex gap-4">
                        <x-splade-submit :label="__('Reopen Ticket')" secondary />
                    </div>
                </x-splade-form>
            </div>
        </div>
    @endif

</x-splade-form>
