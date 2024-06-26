<x-splade-modal>
    <section class="space-y-6">
        <header>
            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Avatar') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                {{ __('Select your avatar.') }}
            </p>
        </header>

        <x-splade-form method="patch" :action="route('profile.avatar')" :default="$user" class="mt-6 space-y-6" preserve-scroll>

            <div class="w-100 inline-flex">
                <img src="<?php echo url(''); ?>/avatar/4043229_afro_avatar_male_man_icon.png" width="70" dalt="">
                <x-splade-radio name="avatar" value="1" false-value="0" />
                <img src="<?php echo url(''); ?>/avatar/4043240_avatar_bad_breaking_chemisrty_heisenberg_icon.png" width="70" dalt="">
                <x-splade-radio name="avatar" value="2" false-value="0" />
                <img src="<?php echo url(''); ?>/avatar/4043236_avatar_boy_male_portrait_icon.png" width="70" dalt="">
                <x-splade-radio name="avatar" value="3" false-value="0" />
                <img src="<?php echo url(''); ?>/avatar/4043247_avatar_female_portrait_woman_icon.png" width="70" dalt="">
                <x-splade-radio name="avatar" value="4" false-value="0" />
                <img src="<?php echo url(''); ?>/avatar/4043248_avatar_female_portrait_woman_icon.png" width="70" dalt="">
                <x-splade-radio name="avatar" value="5" false-value="0" />
                <img src="<?php echo url(''); ?>/avatar/4043231_afro_female_person_woman_icon.png" width="70" dalt="">
                <x-splade-radio name="avatar" value="6" false-value="0" />
                <img src="<?php echo url(''); ?>/avatar/4043251_avatar_female_girl_woman_icon.png" width="70" dalt="">
                <x-splade-radio name="avatar" value="7" false-value="0" />
                <img src="<?php echo url(''); ?>/avatar/4043277_avatar_person_pilot_traveller_icon.png" width="70" dalt="">
                <x-splade-radio name="avatar" value="8" false-value="0" />
                <img src="<?php echo url(''); ?>/avatar/4043270_avatar_joker_squad_suicide_woman_icon.png" width="70" dalt="">
                <x-splade-radio name="avatar" value="9" false-value="0" />
                <img src="<?php echo url(''); ?>/avatar/2992459_girl_lady_user_woman_icon.png" width="70" dalt="">
                <x-splade-radio name="avatar" value="10" false-value="0" />                               
            </div>

            <div class="flex items-center gap-4">
                <x-splade-submit :label="__('Save')" />

                @if (session('status') === 'updated')
                    <p class="text-sm text-gray-600">
                        {{ __('Saved.') }}
                    </p>
                @endif
            </div>

        </x-splade-form>
    </section>
</x-splade-modal>