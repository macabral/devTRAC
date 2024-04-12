<x-splade-modal>
    <p class="mt-1 text-sm text-gray-600">
        {{ __("Files") }}
    </p>
    <br>
    <x-splade-toggle :data="false">

        <table class="hover:table-fixed">
            <tbody>
                <tr>
                    
                    @if ($ret['file'] != '')
                        <td>
                            <a href="{{ route('files.download', $ret['id']) }}" donwload title="Download Zip">
                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M5.625 15C5.625 14.5858 5.28921 14.25 4.875 14.25C4.46079 14.25 4.125 14.5858 4.125 15H5.625ZM4.875 16H4.125H4.875ZM19.275 15C19.275 14.5858 18.9392 14.25 18.525 14.25C18.1108 14.25 17.775 14.5858 17.775 15H19.275ZM11.1086 15.5387C10.8539 15.8653 10.9121 16.3366 11.2387 16.5914C11.5653 16.8461 12.0366 16.7879 12.2914 16.4613L11.1086 15.5387ZM16.1914 11.4613C16.4461 11.1347 16.3879 10.6634 16.0613 10.4086C15.7347 10.1539 15.2634 10.2121 15.0086 10.5387L16.1914 11.4613ZM11.1086 16.4613C11.3634 16.7879 11.8347 16.8461 12.1613 16.5914C12.4879 16.3366 12.5461 15.8653 12.2914 15.5387L11.1086 16.4613ZM8.39138 10.5387C8.13662 10.2121 7.66533 10.1539 7.33873 10.4086C7.01212 10.6634 6.95387 11.1347 7.20862 11.4613L8.39138 10.5387ZM10.95 16C10.95 16.4142 11.2858 16.75 11.7 16.75C12.1142 16.75 12.45 16.4142 12.45 16H10.95ZM12.45 5C12.45 4.58579 12.1142 4.25 11.7 4.25C11.2858 4.25 10.95 4.58579 10.95 5H12.45ZM4.125 15V16H5.625V15H4.125ZM4.125 16C4.125 18.0531 5.75257 19.75 7.8 19.75V18.25C6.61657 18.25 5.625 17.2607 5.625 16H4.125ZM7.8 19.75H15.6V18.25H7.8V19.75ZM15.6 19.75C17.6474 19.75 19.275 18.0531 19.275 16H17.775C17.775 17.2607 16.7834 18.25 15.6 18.25V19.75ZM19.275 16V15H17.775V16H19.275ZM12.2914 16.4613L16.1914 11.4613L15.0086 10.5387L11.1086 15.5387L12.2914 16.4613ZM12.2914 15.5387L8.39138 10.5387L7.20862 11.4613L11.1086 16.4613L12.2914 15.5387ZM12.45 16V5H10.95V16H12.45Z" fill="#000000"/>
                                </svg>
                            </a>
                        </td>
                    @endif

                    @if ($ret['status'] != 'Closed')
                    <td>
                        <a href="" donwload title="Adicionar Documento" @click.prevent="toggle">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 -960 960 960"><path d="M450-450H200v-60h250v-250h60v250h250v60H510v250h-60v-250Z"/></svg>
                        </a>
                    </td>
                    @endif
                </tr>
            </tbody>
        </table>

        <div v-show="toggled">
            <x-splade-form  method="post" :action="route('files.upload',['id' => $ret['id']])" class="mt-4 space-y-4" preserve-scroll>
                <x-splade-file name="arquivos[]" multiple filepond max-size="2MB"/>
                <div class="flex items-right">
                    <x-splade-submit>
                        <svg xmlns="http://www.w3.org/2000/svg" height="18" viewBox="0 -960 960 960" width="18"><path d="M840-683v503q0 24-18 42t-42 18H180q-24 0-42-18t-18-42v-600q0-24 18-42t42-18h503l157 157Zm-60 27L656-780H180v600h600v-476ZM479.765-245Q523-245 553.5-275.265q30.5-30.264 30.5-73.5Q584-392 553.735-422.5q-30.264-30.5-73.5-30.5Q437-453 406.5-422.735q-30.5 30.264-30.5 73.5Q376-306 406.265-275.5q30.264 30.5 73.5 30.5ZM233-584h358v-143H233v143Zm-53-72v476-600 124Z"/></svg>
                    </x-splade-submit>
                    &nbsp;
                    <button @click.prevent="setToggle(false)" title="{{ __('Close') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" height="18" viewBox="0 -960 960 960" width="18"><path d="m249-207-42-42 231-231-231-231 42-42 231 231 231-231 42 42-231 231 231 231-42 42-231-231-231 231Z"/></svg>
                    </button>
                </div>
            </x-splade-form>
        </div>

    </x-splade-toggle>  
    <br>
    <table class="hover:table-fixed">
        <tbody>
            @for ($i = 0; $i<count($ret['files']); $i++)
                <tr>
                    <td style="width:25px;">
                        <a href="{{ route('files.delete', ['id' => $ret['id'],'nomearq' => $ret['files'][$i][0]]) }}" title="Excluir Documento">
                            <svg xmlns="http://www.w3.org/2000/svg" height="18" viewBox="0 -960 960 960" width="18"><path d="m361-299 119-121 120 121 47-48-119-121 119-121-47-48-120 121-119-121-48 48 120 121-120 121 48 48ZM261-120q-24 0-42-18t-18-42v-570h-41v-60h188v-30h264v30h188v60h-41v570q0 24-18 42t-42 18H261Zm438-630H261v570h438v-570Zm-438 0v570-570Z"/></svg>
                        </a>
                    </td>
                    <td>

                        @if(substr($ret['files'][$i][0], strrpos($ret['files'][$i][0], '.') + 1) == 'pdf')
                            <a href="{{ url('/') . '/uploads/downloads/' . auth('sanctum')->user()->id . '/' . $ret['files'][$i][0] }}" onclick="window.open(this.href, 'new', 'popup'); return false;">{{ $ret['files'][$i][0] }}</a>
                        @else
                            <a href="{{ url('/') . '/uploads/downloads/' . auth('sanctum')->user()->id . '/' . $ret['files'][$i][0] }}" download>{{ $ret['files'][$i][0] }}</a>
                        @endif

                    </td>
                </tr>
            @endfor
        </tbody>
    </table>

</x-splade-modal>