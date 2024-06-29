<x-splade-input id="title" name="title" type="text" :label="__('Type')" required autofocus autocomplete="title" />
<x-splade-select id="status" name="status" :options="['Enabled', 'Disabled']" :label="__('Status')" />
<div class="flex items-center gap-4">

    <x-splade-submit :label="__('Save')" />

</div>