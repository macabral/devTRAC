<x-splade-textarea id="title" name="title" type="text" autosize :label="__('Title')" required autofocus autocomplete="title" />
<x-splade-textarea id="description" name="description" autosize :label="__('Description')" required autocomplete="description" />
<x-splade-select id="types_id" name="types_id" :options="$types" option-label="title" option-value="id" :label="__('Type')" />
<x-splade-select id="releases_id" name="releases_id" :options="$releases" option-label="version" option-value="id" :label="__('Release')" />
<x-splade-select id="resp_id" name="resp_id" :options="$devs" option-label="name" option-value="users_id" :label="__('Assign to')" />
<x-splade-select id="status" name="status" :options="['Open', 'Testing', 'Closed']" :label="__('Status')" />

@if ($ret['id'] == 0)
    <x-splade-file name="arquivos[]" multiple filepond max-size="2MB"/>
@endif

<div class="flex items-center gap-4">
    <x-splade-submit :label="__('Save')" />
</div>