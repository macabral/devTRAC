<div class="pt-1 pb-1">
    @foreach ($logs as $item)

        <div class="pt-2 text-sm text-gray-500"> 
            {{ $item->id }}: {{ date('d/m/Y H:i', strtotime($item->created_at)) }}, {{ $item->name }}<hr>
            <div class="text-sm text-gray-900" style="line-height:1.375; white-space:pre-wrap; padding: 1em;">
                {!! (nl2br($item->description)) !!}
            </div>
        </div>
            
    @endforeach
</div>