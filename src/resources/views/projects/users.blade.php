<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('New Project') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl" dusk="update-profile-information">
                    <section>
                        <header>
                            <p class="mt-1 text-sm text-gray-600">
                                {{ __("") }}
                            </p>
                        </header>
                    
                        <x-splade-modal max-width="5xl">
                            <br>
                            <x-splade-table :for="$ret" striped>
                                @cell('name', $ret)
                                <div class="inline-flex">
                                    @if($ret->avatar == 1)
                                        <img src="<?php echo url(''); ?>/avatar/4043229_afro_avatar_male_man_icon.png" width="30" dalt="">
                                    @elseif($ret->avatar == 2)
                                        <img src="<?php echo url(''); ?>/avatar/4043240_avatar_bad_breaking_chemisrty_heisenberg_icon.png" width="30" dalt="">
                                    @elseif($ret->avatar == 3)
                                        <img src="<?php echo url(''); ?>/avatar/4043236_avatar_boy_male_portrait_icon.png" width="30" dalt="">
                                    @elseif($ret->avatar == 4)
                                        <img src="<?php echo url(''); ?>/avatar/4043247_avatar_female_portrait_woman_icon.png" width="30" dalt="">
                                    @elseif($ret->avatar == 5)
                                        <img src="<?php echo url(''); ?>/avatar/4043248_avatar_female_portrait_woman_icon.png" width="30" dalt="">
                                    @elseif($ret->avatar == 6)
                                        <img src="<?php echo url(''); ?>/avatar/4043231_afro_female_person_woman_icon.png" width="30" dalt="">      
                                    @elseif($ret->avatar == 7)
                                        <img src="<?php echo url(''); ?>/avatar/4043251_avatar_female_girl_woman_icon.png" width="30" dalt="">
                                    @elseif($ret->avatar == 8)
                                        <img src="<?php echo url(''); ?>/avatar/4043277_avatar_person_pilot_traveller_icon.png" width="30" dalt="">                                                                       
                                    @elseif($avatar == 9)
                                        <img src="<?php echo url(''); ?>/avatar/4043270_avatar_joker_squad_suicide_woman_icon.png" width="30" dalt="">
                                    @elseif($ret->avatar == 10)
                                        <img src="<?php echo url(''); ?>/avatar/2992459_girl_lady_user_woman_icon.png" width="30" dalt="">                                                                 
                                    @endif                                    
                                    &nbsp;{{ $ret->name }}
                                </div>
                                @endcell
                                @cell('gp',$ret)
                                    @if($ret->gp == 1)
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect width="24" height="24" fill="white"/>
                                        <path d="M5 13.3636L8.03559 16.3204C8.42388 16.6986 9.04279 16.6986 9.43108 16.3204L19 7" stroke="#000000" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    @endif
                                @endcell
                                @cell('relator',$ret)
                                    @if($ret->relator == 1)
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect width="24" height="24" fill="white"/>
                                        <path d="M5 13.3636L8.03559 16.3204C8.42388 16.6986 9.04279 16.6986 9.43108 16.3204L19 7" stroke="#000000" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    @endif
                                @endcell
                                @cell('dev',$ret)
                                    @if($ret->dev == 1)
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect width="24" height="24" fill="white"/>
                                        <path d="M5 13.3636L8.03559 16.3204C8.42388 16.6986 9.04279 16.6986 9.43108 16.3204L19 7" stroke="#000000" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    @endif
                                @endcell
                                @cell('tester',$ret)
                                    @if($ret->tester == 1)
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect width="24" height="24" fill="white"/>
                                        <path d="M5 13.3636L8.03559 16.3204C8.42388 16.6986 9.04279 16.6986 9.43108 16.3204L19 7" stroke="#000000" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    @endif
                                @endcell                                                                                        
                            </x-splade-table>
                        </x-splade-modal>
                        
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>