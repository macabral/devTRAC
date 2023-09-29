<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use App\Library\TracMail;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, TracMail $TracMailInstance)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);
        
        // enviar email ao Administrador
        try {

            $to = User::select('email')->where('admin', '=', 1)->get();

            $mailData = [
                'to' => $to[0]['email'],
                'cc' => null,
                'subject' => 'devTRAC: Novo Usuário',
                'title' => "Novo Usuário",
                'body' => "Você está recebendo esse email porque um novo usuário se registrou no devTRAC com o email $request->email.",
                'priority' => 0,
                'attachments' => null
            ];
                
            $TracMailInstance->save($mailData);

        } catch (\Exception $e) {

        }

        return redirect(RouteServiceProvider::HOME);
    }
}
