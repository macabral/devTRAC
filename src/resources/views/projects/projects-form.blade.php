<x-splade-input id="title" name="title" type="text" :label="__('Title')" required autofocus autocomplete="title" />
<x-splade-textarea id="description" name="description" autosize :label="__('Description')" autocomplete="description" />
<x-splade-select id="status" name="status" :options="['Enabled', 'Disabled']" :label="__('Status')" />
<div class="flex items-center gap-4">

    <x-splade-submit :label="__('Save')" />

</div>