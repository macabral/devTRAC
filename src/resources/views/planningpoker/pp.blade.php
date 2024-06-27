

    <p class="mt-1 text-xl text-gray-600">
        {{ __("Planning Poker") }}
    </p>
    <br>
    <?php
        $total = 0; 
        $contsp = 0;
    ?>

    @if (Session::get('ret')[0]['gp'] != 1)
        
        <x-splade-button class="ml-3">
            <x-splade-defer manual stay @success="$splade.emit('team-member-added')">
                <p v-if="response.data"  />
                <button @click.prevent="reload">Reload</button>
            </x-splade-defer>
        </x-splade-button>
        <br><br>
        <hr>
        <br>
    @endif

    @if (Session::get('ret')[0]['gp'] == 1)
    
        <x-splade-button class="ml-3">
            <x-splade-defer manual stay @success="$splade.emit('team-member-added')">
                <p v-if="response.data"  />
                <button @click.prevent="reload">Reload</button>
            </x-splade-defer>
        </x-splade-button>

        <x-splade-button class="ml-3">
            <x-splade-defer manual url="{{ route('planningpoker.start', base64_encode($ret->id)) }}" stay @success="$splade.emit('team-member-added')">
                <p v-if="response.data"  />
                <button @click.prevent="reload">START</button>
            </x-splade-defer>
        </x-splade-button>

        <x-splade-button class="ml-3">
            <x-splade-defer manual url="{{ route('planningpoker.show', base64_encode($ret->id)) }}"  stay @success="$splade.emit('team-member-added')">
                <button @click.prevent="reload">SHOW</button>
                <p v-if="response.data" />
            </x-splade-defer>
        </x-splade-button>

        <x-splade-button class="ml-3">
            <x-splade-defer manual url="{{ route('planningpoker.end', base64_encode($ret->id)) }}"  stay @success="$splade.emit('team-member-added')">
                <button @click.prevent="reload">END</button>
            </x-splade-defer>
        </x-splade-button>
        <br><br>
        <hr>
        <br>
    @endif


    <x-splade-rehydrate  on="team-member-added">
    <table>
    @foreach($pp as $item)
        <?php
            if($item->valorsp != 0) {
                $total += $item->valorsp;
                $contsp++;
            }
        ?>
        <tr>
            <td>
                <div class="inline-flex">
                    @if($item->avatar == 1)
                        <img src="<?php echo url(''); ?>/avatar/4043229_afro_avatar_male_man_icon.png" width="30" dalt="">
                    @elseif($item->avatar == 2)
                        <img src="<?php echo url(''); ?>/avatar/4043240_avatar_bad_breaking_chemisrty_heisenberg_icon.png" width="30" dalt="">
                    @elseif($item->avatar == 3)
                        <img src="<?php echo url(''); ?>/avatar/4043236_avatar_boy_male_portrait_icon.png" width="30" dalt="">
                    @elseif($item->avatar == 4)
                        <img src="<?php echo url(''); ?>/avatar/4043247_avatar_female_portrait_woman_icon.png" width="30" dalt="">
                    @elseif($item->avatar == 5)
                        <img src="<?php echo url(''); ?>/avatar/4043248_avatar_female_portrait_woman_icon.png" width="30" dalt="">
                    @elseif($item->avatar == 6)
                        <img src="<?php echo url(''); ?>/avatar/4043231_afro_female_person_woman_icon.png" width="30" dalt="">      
                    @elseif($item->avatar == 7)
                        <img src="<?php echo url(''); ?>/avatar/4043251_avatar_female_girl_woman_icon.png" width="30" dalt="">
                    @elseif($item->avatar == 8)
                        <img src="<?php echo url(''); ?>/avatar/4043277_avatar_person_pilot_traveller_icon.png" width="30" dalt="">                                                                       
                    @elseif($item->avatar == 9)
                        <img src="<?php echo url(''); ?>/avatar/4043270_avatar_joker_squad_suicide_woman_icon.png" width="30" dalt="">
                    @elseif($item->avatar == 10)
                        <img src="<?php echo url(''); ?>/avatar/2992459_girl_lady_user_woman_icon.png" width="30" dalt="">                                                                 
                    @endif  
                    &nbsp;
                    {{ $item->name }}
                </div>
            </td>

            <td class='w-20 pl-10'>
                @if($item->users_id == auth('sanctum')->user()->id || Session::get('ret')[0]['gp'] == 1)
                    {{ $item->valorsp }}
                @elseif($ret->planning_poker_status != 0)
                    {{ $item->valorsp }}
                @endif
            </td>

            <td>
                @if($item->users_id == auth('sanctum')->user()->id && $ret->planning_poker_status == 0)
                    <x-splade-button class="ml-3">
                        <Link  modal href="{{ route('planningpoker.vote', base64_encode($item->id)) }}" title="{{ __('Planning Poker Vote') }}">
                        Votar
                        </Link>
                    </x-splade-button>
                @endif
            </td>
        </tr>
    @endforeach
    <?php
        if ($contsp != 0) {
            $media = round($total/$contsp,0);
        } else {
            $media = 0;
        }
    ?>
    @if($ret->planning_poker_status != 0)
        <tr>
            <td></td>
            <td>Média:</td>
            <td class="text-right">
                {{ $media }}
            </td>
        </tr>
    @endif
    </table>
    </x-splade-rehydrate>
