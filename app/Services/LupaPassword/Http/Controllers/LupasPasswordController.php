<?php

namespace App\Services\LupaPassword\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LupasPasswordController extends Controller {
    public function getLupaPass(){
        return view('Services.LupaPassword.mainLupaPassword');
    }

    public function updatePassword(Request $request)
    {
        if (!session()->has('user_for_password_change')) {
            return redirect()->route('auth.login')->withErrors('Sesi tidak valid, silakan login kembali.');
        }

        $request->validate([
            'NEWPASS' => 'required|string|min:6',
            'CONFPASS' => 'required|string|same:NEWPASS',
        ], [
            'NEWPASS.required' => 'Password baru tidak boleh kosong.',
            'NEWPASS.min' => 'Password baru minimal 6 karakter.',
            'CONFPASS.same' => 'Konfirmasi password tidak cocok dengan password baru.',
        ]);

        $username = $request->session()->get('user_for_password_change');
        DB::table('user_complaint')
            ->where('USERNAME', $username)
            ->update([
                'PASSWORD'      => sha1($request->NEWPASS),
                'PASSWORD_REAL' => $request->NEWPASS,
                'VALIDASI'      => 'Y',
            ]);

        $request->session()->forget('user_for_password_change');
        $user = DB::table('user_complaint')->where('USERNAME', $username)->first();
        $role = (preg_match('/[0-9]/', $user->ID_BAGIAN)) ? 'unit_kerja' : 'direksi';

        session(['user' => $user, 'role' => $role]);
        $request->session()->regenerate();

        return redirect()->route('admin.dashboard')->with('success', 'Password berhasil diubah. Selamat datang!');
    }
}
