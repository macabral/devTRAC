<x-splade-form method="post" :action="route('logtickets.create',  $ret->id)"  class="mt-4 space-y-4" preserve-scroll>
    <div style="width: 50%;">
        <x-splade-textarea id="description" name="description" autosize rows="7" :label="__('Add Comments')" required autocomplete="description" />
    </div>
    <x-splade-submit :label="__('Save')" />
</x-splade-form>

<div class="inline-flex pt-4">

    <div class="flex items-center gap-4"> 

        @if ($ret->status == 'Open' && Session::get('ret')[0]['dev'] == '1')
            <x-splade-form method="get" :action="route('logtickets.update', [$ret->id, 'Testing'])"  class="mt-4 space-y-4" preserve-scroll
                confirm="Enviar para Teste"
                confirm-text="Confirma enviar o tíquete para teste?"
                confirm-button="Confirmar"
                cancel-button="Cancelar"
            >
                <div class="inline-flex gap-4">
                    <x-splade-submit :label="__('Submit for Testing')" />
                </div>
            </x-splade-form>
        @endif

        @if ($ret->status == 'Testing' && Session::get('ret')[0]['tester'] == '1')
            <div class="inline-flex gap-4">
                <div>
                    <x-splade-form method="get" :action="route('logtickets.update', [$ret->id, 'Closed'])"  class="mt-4 space-y-4" preserve-scroll
                        confirm="Fechar Tíquete"
                        confirm-text="Confirma Fechar o tíquete?"
                        confirm-button="Confirmar"
                        cancel-button="Cancelar"
                    >
                        <div class="inline-flex gap-4">
                            <x-splade-submit :label="__('Close Ticket')" />
                        </div>
                    </x-splade-form>
                </div>
                <div>
                    <x-splade-form method="get" :action="route('logtickets.update', [$ret->id, 'Open'])"  class="mt-4 space-y-4" preserve-scroll
                        confirm="Reabrir Tíquete"
                        confirm-text="Confirma Reabrir o tíquete?"
                        confirm-button="Confirmar"
                        cancel-button="Cancelar"
                    >
                        <div class="inline-flex gap-4">
                            <x-splade-submit :label="__('Reopen Ticket')" />
                        </div>
                    </x-splade-form>
                </div>
            </div>
        @endif

    </div>
</div>

