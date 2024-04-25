<x-splade-modal max-width="7xl">
    <br>
    <div class="flex justify-between">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Projects') }}
        </h2>

        <x-splade-button>
            <Link slideover href="{{ route('users.newprojects', $userId) }}" class="pc-6 text-white rounded-md">
                {{ __('Associate Project') }}
            </Link>
        </x-splade-button>
    </div>
   
    <div class="py-12">
        <div class="max-w-1xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <section>
                <x-splade-table :for="$ret" striped>
                    @cell('action', $ret)
                        <div class="flex flex-row">
                            <div class="basis-1/2 text-center">
                                <Link modal href="{{ route('users.project-delete', [ $ret->pivot->users_id , base64_encode($ret->id) ]) }}" title="{{ __('Delete') }}">
                                    <svg xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 24 24" width="16px" height="16px"><path d="M 10.806641 2 C 10.289641 2 9.7956875 2.2043125 9.4296875 2.5703125 L 9 3 L 4 3 A 1.0001 1.0001 0 1 0 4 5 L 20 5 A 1.0001 1.0001 0 1 0 20 3 L 15 3 L 14.570312 2.5703125 C 14.205312 2.2043125 13.710359 2 13.193359 2 L 10.806641 2 z M 4.3652344 7 L 5.8925781 20.263672 C 6.0245781 21.253672 6.877 22 7.875 22 L 16.123047 22 C 17.121047 22 17.974422 21.254859 18.107422 20.255859 L 19.634766 7 L 4.3652344 7 z"/></svg>
                                </Link>
                            </div>                            
                        </div>
                    @endcell
                </x-splade-table>
            </section>
        </div> 
    </div>
</x-splade-modal>

