<?php

namespace app\Services\Humas\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Ticketing\Models\KlasifikasiPengaduan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

class KlasifikasiPengaduanController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'KLASIFIKASI_PENGADUAN' => 'required|string|max:100|unique:klasifikasi_pengaduan',
        ], [
            'KLASIFIKASI_PENGADUAN.required' => 'Kolom klasifikasi pengaduan wajib diisi.',
            'KLASIFIKASI_PENGADUAN.unique' => 'Klasifikasi pengaduan ini sudah ada.',
        ]);

        try {
            DB::beginTransaction();

            $today = Carbon::now()->format('Ymd');
            $lastRecord = KlasifikasiPengaduan::where('ID_KLASIFIKASI', 'LIKE', $today . '%')
                ->latest('ID_KLASIFIKASI')
                ->first();

            $sequence = $lastRecord ? (int)substr($lastRecord->ID_KLASIFIKASI, -6) + 1 : 1;
            $newId = $today . str_pad($sequence, 6, '0', STR_PAD_LEFT);

            KlasifikasiPengaduan::create([
                'ID_KLASIFIKASI' => $newId,
                'KLASIFIKASI_PENGADUAN' => strtoupper($request->KLASIFIKASI_PENGADUAN),
                'STATUS' => 1,
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Klasifikasi pengaduan berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menambahkan data: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id_klasifikasi)
    {
        $request->validate([
            'KLASIFIKASI_PENGADUAN' => [
                'required',
                'string',
                'max:100',
                Rule::unique('klasifikasi_pengaduan')->ignore($id_klasifikasi, 'ID_KLASIFIKASI'),
            ],
        ], [
            'KLASIFIKASI_PENGADUAN.required' => 'Kolom klasifikasi pengaduan wajib diisi.',
            'KLASIFIKASI_PENGADUAN.unique' => 'Klasifikasi pengaduan ini sudah ada.',
        ]);

        try {
            $klasifikasi = KlasifikasiPengaduan::findOrFail($id_klasifikasi);
            $klasifikasi->update([
                'KLASIFIKASI_PENGADUAN' => strtoupper($request->KLASIFIKASI_PENGADUAN),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diperbarui.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id_klasifikasi)
    {
        try {
            $klasifikasi = KlasifikasiPengaduan::findOrFail($id_klasifikasi);

            $klasifikasi->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }
}
