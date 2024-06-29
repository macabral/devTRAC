<x-splade-input id="title" name="title" type="text" :label="__('Title')" required autofocus autocomplete="title" />
<x-splade-input id="datadoc" name="datadoc" type="date" :label="__('Data')" required autofocus autocomplete="start" />
<x-splade-select id="tipodocs_id" name="tipodocs_id" :options="$tipodocs" option-label="title" option-value="id" :label="__('Type')" required />
@if ($ret['id'] == 0)
    <x-splade-file name="arquivos[]" multiple filepond max-size="2MB"/>
@endif
<div class="flex items-center gap-4">

    <x-splade-submit :label="__('Save')" />

</div>