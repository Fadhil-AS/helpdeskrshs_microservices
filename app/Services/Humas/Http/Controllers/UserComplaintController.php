<?php
namespace App\Services\Humas\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Ticketing\Models\UserComplaint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Services\Humas\Traits\NotifikasiWhatsappPelapor;

class UserComplaintController extends Controller{

    use NotifikasiWhatsappPelapor;

    public function getUserComplaint()
    {
        return redirect()->route('humas.unit-kerja-humas');
    }

    public function storeUserComplaint(Request $request)
    {
        $validatedData = $request->validate([
            'USERNAME' => 'required|string|unique:user_complaint,USERNAME',
            'PASSWORD' => 'required|string|min:6',
            'NAME' => 'required|string|max:255',
            'ID_BAGIAN' => 'required|string|exists:unit_kerja,ID_BAGIAN',
            'NIP' => 'required|string|unique:user_complaint,NIP',
            'NO_TLPN' => 'required|string|max:20',
            'SPESIAL_CODE' => 'nullable|string',
        ], [
            'USERNAME.different' => 'Username dan Password tidak boleh sama.'
        ]);

        $lastUser = UserComplaint::orderBy('NO_REGISTER', 'desc')->first();
        $newSequenceNumber = 1;

        if ($lastUser) {
            $lastSequence = (int) substr($lastUser->NO_REGISTER, -8);
            $newSequenceNumber = $lastSequence + 1;
        }

        $prefix = date('ym');
        $newNoRegister = $prefix . '_' . sprintf('%08d', $newSequenceNumber);

        $dataToCreate = [
            'NO_REGISTER' => $newNoRegister,
            'USERNAME' => $validatedData['USERNAME'],
            'NAME' => $validatedData['NAME'],
            'PASSWORD' => sha1($validatedData['PASSWORD']),
            'PASSWORD_REAL' => $validatedData['PASSWORD'],
            'ID_BAGIAN' => $validatedData['ID_BAGIAN'],
            'NIP' => $validatedData['NIP'],
            'NO_TLPN' => $validatedData['NO_TLPN'],
            'VALIDASI' => 'N',
            'SPESIAL_CODE' => $validatedData['SPESIAL_CODE'] ?? null,
        ];

        UserComplaint::create($dataToCreate);

        $pesan = "Yth. Bapak/Ibu \n" . $validatedData['NAME'] . ",\n\n";
        $pesan .= "Akun admin unit kerja Anda telah berhasil dibuat dengan detail sebagai berikut:\n\n";
        $pesan .= "Username: " . $validatedData['USERNAME'] . "\n";
        $pesan .= "Password: ". $validatedData['PASSWORD'] ."\n\n";
        $pesan .= "Akun Anda akan aktif setelah melakukan login pertama kali dan mengganti password. Terima kasih.\n\n";
        $pesan .= "Pengirim\nRumah Sakit Hasan Sadikin";

        $this->kirimPesanWA($validatedData['NO_TLPN'], $pesan);

        return response()->json([
            'success' => true,
            'message' => 'Admin unit kerja baru berhasil ditambahkan!'
        ]);
    }

    public function updateUserComplaint(Request $request, UserComplaint $userComplaint)
    {
        $request->validate([
            'USERNAME' => ['required', 'string', Rule::unique('user_complaint')->ignore($userComplaint->NO_REGISTER, 'NO_REGISTER')],
            'NAME' => 'required|string|max:255',
            'ID_BAGIAN' => 'required|string|exists:unit_kerja,ID_BAGIAN',
            'NIP' => ['required', 'string', Rule::unique('user_complaint')->ignore($userComplaint->NO_REGISTER, 'NO_REGISTER')],
            'NO_TLPN' => 'required|string|max:20',
            'VALIDASI' => 'required|in:Y,N',
            'PASSWORD' => 'nullable|string|min:6',
        ]);

        $dataToUpdate = $request->except('PASSWORD');

        $pesan = "Yth. Bapak/Ibu \n" . $userComplaint->NAME . ",\n\n";
        $pesan .= "Data akun admin unit kerja Anda telah berhasil diperbarui. ";
        $pesan .= "Jika Anda tidak merasa melakukan perubahan ini, harap segera hubungi tim Humas. Terima kasih.\n\n";
        $pesan .= "Pengirim\nRumah Sakit Hasan Sadikin";

        $this->kirimPesanWA($userComplaint->NO_TLPN, $pesan);

        if ($request->filled('PASSWORD')) {
            $dataToUpdate['PASSWORD'] = sha1($request->PASSWORD);
            $dataToUpdate['PASSWORD_REAL'] = $request->PASSWORD;
        }

        $userComplaint->update($dataToUpdate);

        return response()->json(['success' => true, 'message' => 'Data admin unit kerja berhasil diperbarui!']);
    }

    public function resetUserPassword(UserComplaint $userComplaint)
    {
        try {
            $defaultPassword = 'rshs_'.date('Y');

            $userComplaint->update([
                'PASSWORD'      => sha1($defaultPassword),
                'PASSWORD_REAL' => $defaultPassword,
                'VALIDASI'      => 'N',
            ]);

            $pesan = "Yth. Bapak/Ibu \n" . $userComplaint->NAME . ",\n\n";
            $pesan .= "Password untuk akun admin unit kerja Anda (" . $userComplaint->USERNAME . ") telah berhasil direset.\n\n";
            $pesan .= "Password baru Anda adalah: " . $defaultPassword . "\n\n";
            $pesan .= "Harap segera login dan ganti password Anda. Akun Anda perlu divalidasi kembali oleh tim Humas. Terima kasih.\n\n";
            $pesan .= "Pengirim\nRumah Sakit Hasan Sadikin";

            $this->kirimPesanWA($userComplaint->NO_TLPN, $pesan);

            return response()->json([
                'success' => true,
                'message' => 'Password untuk user ' . $userComplaint->USERNAME . ' berhasil direset.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mereset password: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroyUserComplaint(UserComplaint $userComplaint){
        $userName = $userComplaint->NAME;

        $pesan = "Yth. Bapak/Ibu \n" . $userComplaint->NAME . ",\n\n";
        $pesan .= "Akun admin unit kerja Anda telah dihapus dari sistem. ";
        $pesan .= "Terima kasih atas kontribusi Anda. Jika ini adalah sebuah kesalahan, harap hubungi tim Humas.\n\n";
        $pesan .= "Pengirim\nRumah Sakit Hasan Sadikin";

        $this->kirimPesanWA($userComplaint->NO_TLPN, $pesan);

        $userComplaint->delete();
        return redirect()->route('humas.unit-kerja-humas')
                         ->with('success', 'Admin unit kerja"' . $userName . '" berhasil dihapus.');
    }
}
