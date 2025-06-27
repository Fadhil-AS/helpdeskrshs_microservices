<?php

namespace App\Services\Login\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller {
    public function getLogin(){
        if (session()->has('user')) {
            $role = session('role');
            if ($role === 'humas') {
                return redirect()->route('humas.pelaporan-humas');
            } elseif ($role === 'direksi' || $role === 'unit_kerja') {
                return redirect()->route('admin.dashboard');
            }
        }
        return view('Services.Login.mainLogin');
    }

    public function postLogin(Request $request)
    {
        $credentials = $request->validate([
            'USERNAME' => 'required',
            'password' => 'required',
        ]);

        $plainPassword = $credentials['password'];

        $humas = DB::table('humas')->where('USERNAME', $credentials['USERNAME'])->first();
        if ($humas) {
            $storedPassword = $humas->PASSWORD;
            $passwordIsCorrect = false;

            if (strlen($storedPassword) === 40 && ctype_xdigit($storedPassword)) {
                if (sha1($plainPassword) === $storedPassword) {
                    $passwordIsCorrect = true;
                }
            }
            else if (strlen($storedPassword) === 32 && ctype_xdigit($storedPassword)) {
                if (md5($plainPassword) === $storedPassword) {
                    $passwordIsCorrect = true;
                }
            }
            else if (Hash::check($plainPassword, $storedPassword)) {
                $passwordIsCorrect = true;
            }

            if ($passwordIsCorrect) {
                session(['user' => $humas, 'role' => 'humas']);
                $request->session()->regenerate();
                return redirect()->intended(route('humas.pelaporan-humas'));
            }
        }

        $userComplaint = DB::table('user_complaint')->where('USERNAME', $credentials['USERNAME'])->first();
        if ($userComplaint) {
            if ($userComplaint->VALIDASI !== 'Y') {
                return back()->withErrors(['USERNAME' => 'Akun Anda tidak aktif atau belum divalidasi.'])->onlyInput('USERNAME');
            }

            $storedPassword = $userComplaint->PASSWORD;
            $passwordIsCorrect = false;

            if (strlen($storedPassword) === 40 && ctype_xdigit($storedPassword)) {
                if (sha1($plainPassword) === $storedPassword) {
                    $passwordIsCorrect = true;
                }
            }
            else if (strlen($storedPassword) === 32 && ctype_xdigit($storedPassword)) {
                if (md5($plainPassword) === $storedPassword) {
                    $passwordIsCorrect = true;
                }
            }
            else if (Hash::check($plainPassword, $storedPassword)) {
                $passwordIsCorrect = true;
            }

            if ($passwordIsCorrect) {
                $role = (preg_match('/[0-9]/', $userComplaint->ID_BAGIAN)) ? 'unit_kerja' : 'direksi';
                session(['user' => $userComplaint, 'role' => $role]);
                $request->session()->regenerate();
                return redirect()->intended(route('admin.dashboard'));
            }
        }

        return back()->withErrors([
            'USERNAME' => 'Username atau Password yang Anda masukkan salah.',
        ])->onlyInput('USERNAME');
    }

    public function logout(Request $request)
    {
        session()->flush();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('auth.login');
    }
}
