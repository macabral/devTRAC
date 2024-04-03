<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use ProtoneMedia\Splade\Facades\Toast;
use App\Models\Configs;

class ConfigController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function index()
    {
        $ret = Configs::findOrFail(1);

        return view('config.index', [
            'ret' => $ret,
        ]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'storypoint' => 'required|max:10',
        ]);
      
        $input = $request->all();

        $ret = Configs::findOrFail(1);

        try {
            
            $ret->fill($input);

        } catch (\Exception $e) {

            return response()->json(['messagem' => $e], 422);
            
        }

        $ret->save();

        Toast::title(__('Config saved!'))->autoDismiss(5);

        return redirect()->back();
    }

}
