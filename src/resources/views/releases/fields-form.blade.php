<x-splade-input id="version" name="version" type="text" :label="__('Version')" required autofocus autocomplete="title" />
<x-splade-textarea id="description" name="description" autosize :label="__('Description')" autocomplete="description" />
<x-splade-select id="status" name="status" :options="['Open', 'Closed','Waiting']" :label="__('Status')" />
<div class="flex items-center gap-4">

    <x-splade-submit :label="__('Save')" />

</div>