<?php
namespace App\Services\Humas\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Ticketing\Models\UserComplaint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserComplaintController extends Controller{
    public function getUserComplaint()
    {
        return redirect()->route('humas.unit-kerja-humas');
    }

    public function storeUserComplaint(Request $request)
    {
        $request->validate([
            'USERNAME' => 'required|string|unique:user_complaint,USERNAME|different:PASSWORD',
            'PASSWORD' => 'required|string|min:6|unique:user_complaint,PASSWORD_REAL',
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
            'USERNAME' => $request->USERNAME,
            'NAME' => $request->NAME,
            'PASSWORD' => sha1($request->PASSWORD),
            'PASSWORD_REAL' => $request->PASSWORD,
            'ID_BAGIAN' => $request->ID_BAGIAN,
            'NIP' => $request->NIP,
            'NO_TLPN' => $request->NO_TLPN,
            'VALIDASI' => 'N',
            'SPESIAL_CODE' => $request->SPESIAL_CODE,
        ];

        UserComplaint::create($dataToCreate);

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

        if ($request->filled('PASSWORD')) {
            $dataToUpdate['PASSWORD'] = sha1($request->PASSWORD);
            $dataToUpdate['PASSWORD_REAL'] = $request->PASSWORD;
        }

        $userComplaint->update($dataToUpdate);

        return response()->json(['success' => true, 'message' => 'Data admin unit kerja berhasil diperbarui!']);
    }

    public function destroyUserComplaint(UserComplaint $userComplaint){
        $userName = $userComplaint->NAME;
        $userComplaint->delete();
        return redirect()->route('humas.unit-kerja-humas')
                         ->with('success', 'Admin unit kerja"' . $userName . '" berhasil dihapus.');
    }
}
