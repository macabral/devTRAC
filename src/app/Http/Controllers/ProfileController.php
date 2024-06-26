<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use ProtoneMedia\Splade\SpladeTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Models\User;
use ProtoneMedia\Splade\Facades\Toast;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     *
     * @return \Illuminate\View\View
     */
    public function edit(Request $request)
    {

        $userId = auth('sanctum')->user()->id;

        $ret = User::where('id', $userId)->with('projects')->get();

        return view('profile.edit', [
            'user' => $request->user(),
            'ret' => SpladeTable::for($ret[0]->projects)
                ->perPageOptions([7, 10, 50, 100, 200])
                ->defaultSort('','desc')
                ->column('title', label: __('Project'), sortable: true, searchable: false, canBeHidden:false)
                ->column('pivot.gp', label: __('gp'), searchable: false)
                ->column('pivot.relator', label: __('relator'), searchable: false)
                ->column('pivot.dev', label: __('dev'), searchable: false)
                ->column('pivot.tester', label: __('tester'), searchable: false)
                ->column('pivot.users_id', hidden: true)
        ]);
    }

    /**
     * Update the user's profile information.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ProfileUpdateRequest $request)
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($id)
    {

        return view('profile.partials.delete-user-form', [
            'user' => $id,
        ]);
    }

    /**
     * Delete the user's account.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current-password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Update the user's avatar.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function avatar(Request $request)
    {

        $userId = auth('sanctum')->user()->id;

        $ret = User::findOrFail($userId);

        $input = $request->all();

        $ret->fill($input);
        
        try {
            
            $ret->save();

        } catch (\Exception $e) {

            Toast::title(__('Error!' . $e->getMessage()))->danger()->autoDismiss(5);

            return response()->json(['messagem' => $e], 422);
            
        }

        return Redirect::route('profile.avatar')->with('status', 'updated');
    }

}
