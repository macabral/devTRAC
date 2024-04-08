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
                <td>{{ $ret->release }}</td>
                <td>{{ $ret->type }}</td>
                <td>{{ $ret->prioridade }}</td>
                <td>{{ $ret->valorsp }}</td>
                <td>{{ $ret->relator }}</td>
                <td>{{ $ret->resp }}</td>
                <td>{{ date('d/m/Y H:i', strtotime($ret->created_at)) }}</td>  
                <td>{{ $ret->status }}</td>
                <td>
                    <Link slideover href="{{ route('files.show', base64_encode($ret->id)) }}" title="{{ __('Files') }}">
                        <center>
                            ({{ $ret->docs }})
                        </center>
                    </Link>
                </td>
            </tr>
        </tbody>
    </table>
</div>