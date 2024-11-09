<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Throwable;

class FirebaseController extends Controller
{
    protected $redirectTo = '/home';

    public function username()
    {
        return 'email';
    }

    public function handleCallback(Request $request)
    {
        $socialTokenId = $request->input('social-login-tokenId', '');
        try {
            $verifiedIdToken = Firebase::auth()->verifyIdToken($socialTokenId);
            $data = $verifiedIdToken->claims();
            $user = User::create([
                'name' => $data->get('name'),
                'email' => $data->get('email'),
                'password' => Str::random(16),
                'email_verified_at' => now(),
            ]);
            Auth::login($user);
            return redirect($this->redirectTo);
        } catch (\InvalidArgumentException $e) {
            report($e);
            return redirect()->route('login');
        } catch (Throwable $e) {
            report($e);
            return redirect()->route('login');
        }
    }
}
