<x-splade-input id="name" name="name" type="text" :label="__('Name')" required autofocus autocomplete="name" />
<x-splade-input id="email" name="email" type="text" :label="__('email')" required autofocus autocomplete="email" />
<x-splade-checkbox name="admin" value="1" false-value="0" :label="__('is Admin?')" />
<x-splade-checkbox name="active" value="1" false-value="0" :label="__('is Active?')" />
<div class="flex items-center gap-4">

    <x-splade-submit :label="__('Save')" />

</div>

