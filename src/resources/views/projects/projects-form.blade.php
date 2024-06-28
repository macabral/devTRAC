<x-splade-input id="title" name="title" type="text" :label="__('Title')" required autofocus autocomplete="title" />
<x-splade-textarea id="description" name="description" autosize :label="__('Description')" autocomplete="description" />
<x-splade-input id="sitelink" type="text" name="sitelink" autosize :label="__('Site link')" autocomplete="sitelink" />
<x-splade-input id="gitlink" type="text" name="gitlink" autosize :label="__('GIT link')" autocomplete="gitlink" />
<x-splade-select id="status" name="status" :options="['Enabled', 'Disabled']" :label="__('Status')" />
<x-splade-input id="media_sp" name="media_sp" min="0" max="255" type="number" :label="__('Average Story Point')" required autofocus autocomplete="media_sp" />
<x-splade-input id="media_pf" name="media_pf" min="0" max="255" type="number" :label="__('Average Function Point')" required autofocus autocomplete="media_sp" />

<div class="flex items-center gap-4">

    <x-splade-submit :label="__('Save')" />

</div>