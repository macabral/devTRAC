<x-splade-input id="version" name="version" type="text" :label="__('Sprint')" required autofocus autocomplete="title" />
<x-splade-textarea id="description" name="description" autosize :label="__('Description')" autocomplete="description" />
<x-splade-input id="start" name="start" type="date" :label="__('Start')" required autofocus autocomplete="start" />
<x-splade-input id="end" name="end" type="date" :label="__('End')" required autofocus autocomplete="end" />
<x-splade-select id="status" name="status" :options="['Open', 'Closed','Waiting']" :label="__('Status')" />
<x-splade-textarea id="lessons" name="lessons" autosize :label="__('Learned Lessons')" autocomplete="lessons" />
<div class="flex items-center gap-4">

    <x-splade-submit :label="__('Save')" />

</div>