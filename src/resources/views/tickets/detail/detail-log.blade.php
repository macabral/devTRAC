<div class="pt-4 pb-4">
    @foreach ($logs as $item)

        <div class="pt-4 text-sm text-gray-500"> 
            {{ $item->id }}: {{ date('d/m/Y H:i', strtotime($item->created_at)) }}, {{ $item->name }}<hr>
            <div class="text-sm text-gray-900" style="line-height:1.375; white-space:pre-wrap; padding: 1em;">
                {!! (nl2br($item->description)) !!}
            </div>
        </div>
            
    @endforeach
</div>