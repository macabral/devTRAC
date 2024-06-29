<div class="pt-2">
    <table class="min-w-full text-center border-collapse">
        <thead class="text-xs text-blue-700  bg-blue-50 dark:bg-blue-700 dark:text-blue-400">
            <tr>
                <th class="px-6 py-3">{{ __('Project') }}</th>
                <th>{{ __('Sprint') }}</th>
                <th>{{ __('Type') }}</th>
                <th>{{ __('Priority') }}</th>
                <th>{{ __('Story Points') }}</th>
                <th>{{ __('Relator') }}</th>
                <th>{{ __('Assign to') }}</th>
                <th>{{ __('Created At') }}</th>
                <th>{{ __('Status') }}</th>
                <th>{{ __('Files') }}</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="px-6 py-3">{{ $ret->project }}</td>
                <td>{{ $ret->sprint }}</td>
                <td>{{ $ret->type }}</td>
                <td>{{ $ret->prioridade }}</td>
                <td>{{ $ret->valorsp }}</td>
                <td>{{ $ret->relator }}</td>
                <td>{{ $ret->resp }}</td>
                <td>{{ date('d/m/Y H:i', strtotime($ret->created_at)) }}</td>  
                <td>{{ $ret->status }}</td>
                <td>
                    <Link slideover href="{{ route('files.show', base64_encode($ret->id)), 'tickets' }}" title="{{ __('Files') }}">
                        <center>
                            ({{ $ret->docs }})
                        </center>
                    </Link>
                </td>
            </tr>
        </tbody>
    </table>
    <div class="flex justify-end align-middle inline-block mr-6">
        @if ($ret->status == 'Open' || $ret->status == 'Testing')
            @if ($ret->resp_id === auth('sanctum')->user()->id)
                <div class="w-100 inline-flex align-middle ">
                        @if($ret->start != 1)
                            <div class="pr-4 pt-3">
                                <Link href="{{ route('tickets.start', base64_encode($ret->id)) }}" title="{{ __('Start Task') }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M2 24v-24l20 12-20 12z"/></svg>
                                </Link>
                            </div>
                        @endif
                        @if($ret->start == 1)
                            <div class="pr-4 pt-3">
                                <Link href="{{ route('tickets.pause', base64_encode($ret->id)) }}" title="{{ __('Pause Task') }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M11 22h-4v-20h4v20zm6-20h-4v20h4v-20z"/></svg>
                                </Link>
                            </div>
                        @endif
                </div>
            @endif
        @endif

    </div>
</div>
