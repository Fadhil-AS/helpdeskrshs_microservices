<?php
namespace App\Services\Humas\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Ticketing\Models\UserComplaint;
use App\Services\Humas\Traits\NotifikasiWhatsappAkunUnitKerja;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserComplaintController extends Controller{

    use NotifikasiWhatsappAkunUnitKerja;

    public function unitKerjaHumas(Request $request)
    {
        $query = UserComplaint::with('unitKerja')->latest('TGL_INSROW');

        if ($request->filled('filter_unit')) {
            $query->where('ID_BAGIAN', $request->filter_unit);
        }

        if ($request->filled('filter_status')) {
            $query->where('VALIDASI', $request->filter_status);
        }

        if ($request->has('search') && $request->input('search') != '') {
            $searchKeyword = '%' . strtolower($request->search) . '%';
            $query->where(function ($q) use ($searchKeyword) {
                $q->whereRaw('LOWER(NAME) LIKE ?', [$searchKeyword])
                  ->orWhereRaw('LOWER(USERNAME) LIKE ?', [$searchKeyword])
                  ->orWhereHas('unitKerja', function ($subQuery) use ($searchKeyword) {
                      $subQuery->whereRaw('LOWER(NAMA_BAGIAN) LIKE ?', [$searchKeyword]);
                  });
            });
        }

        $admins = $query->paginate(10)->appends($request->query());
        if ($request->ajax()) {
            $tableHtml = view('Services.Humas.unitKerjaHumas.partials.adminUKH.admin_table_partial', compact('admins'))->render();
            return response()->json([
                'table_html' => $tableHtml
            ]);
        }

        $parents = UnitKerja::where('IS_PARENT', 'Y')->orderBy('NAMA_BAGIAN', 'asc')->get();
        return view('Services.Humas.unitKerjaHumas.partials.adminUKH.tabelAUKH', compact('admins', 'parents'));
    }

    public function getUserComplaint()
    {
        return redirect()->route('humas.unit-kerja-humas');
    }

    public function storeUserComplaint(Request $request)
    {
        $request->validate([
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

        $newUser = UserComplaint::create($dataToCreate);

        $this->sendNewUserNotification($newUser);

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
        $this->sendProfileUpdateNotification($userComplaint);

        return response()->json(['success' => true, 'message' => 'Data admin unit kerja berhasil diperbarui!']);
    }

    public function resetUserPassword(UserComplaint $userComplaint)
    {
        try {
            $defaultPassword = 'rshs_2025';

            $userComplaint->update([
                'PASSWORD'      => sha1($defaultPassword),
                'PASSWORD_REAL' => $defaultPassword,
                'VALIDASI'      => 'N',
            ]);

            $this->sendPasswordResetNotification($userComplaint, $defaultPassword);

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
        $this->sendAccountDeletionNotification($userComplaint);
        $userComplaint->delete();
        return redirect()->route('humas.unit-kerja-humas')
                         ->with('success', 'Admin unit kerja"' . $userName . '" berhasil dihapus.');
    }
}
