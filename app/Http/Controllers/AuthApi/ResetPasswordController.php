<?php

namespace App\Http\Controllers\AuthApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Str;

// FormRequest
use App\Http\Requests\ResetPassword\UpdateRequest;
use App\Http\Requests\ResetPassword\StoreRequest;

// Models
use App\Models\PasswordReset;
use App\Models\User;

class ResetPasswordController extends Controller
{
    public function notification(StoreRequest $request)
    {
        $reset = [
            'email' => $request->email,
            'token' => Str::random(60),
            'url' => url()->to('/'),
        ];

        $password_reset = PasswordReset::UpdateOrCreate(['email' => $request->email], $reset);

        \Mail::send('emails.password.reset', $reset, function ($message) use($request) {
            $message->from('noreply@amauttasystems.com', 'Quipus seguros');
            $message->to($request->email)->subject('Recuperación de contraseña');
        });
    }

    public function changePassword(UpdateRequest $request)
    {
        $password_reset = PasswordReset::where('token', $request->token)->first();

        $user = User::where('email', $password_reset->email)->first();

        $user->password = bcrypt($request->password);
        $user->save();

        $password_reset->forceDelete();
    }
}
