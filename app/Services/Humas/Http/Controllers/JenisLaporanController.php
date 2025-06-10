<?php

namespace App\Services\Humas\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Ticketing\Models\JenisLaporan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

class JenisLaporanController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'JENIS_LAPORAN' => 'required|string|max:100|unique:jenis_laporan',
        ], [
            'JENIS_LAPORAN.required' => 'Kolom jenis laporan wajib diisi.',
            'JENIS_LAPORAN.unique' => 'Jenis laporan ini sudah ada.',
        ]);

        try {
            DB::beginTransaction();
            $today = Carbon::now()->format('Ymd');
            $lastRecord = JenisLaporan::where('ID_JENIS_LAPORAN', 'LIKE', $today . '%')
                ->latest('ID_JENIS_LAPORAN')
                ->first();

            $sequence = $lastRecord ? (int)substr($lastRecord->ID_JENIS_LAPORAN, -6) + 1 : 1;
            $newId = $today . str_pad($sequence, 6, '0', STR_PAD_LEFT);

            JenisLaporan::create([
                'ID_JENIS_LAPORAN' => $newId,
                'JENIS_LAPORAN' => strtoupper($request->JENIS_LAPORAN),
                'STATUS' => 1,
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Jenis laporan berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Gagal menambahkan data: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id_jenis_laporan)
    {
        $request->validate([
            'JENIS_LAPORAN' => [
                'required',
                'string',
                'max:100',
                Rule::unique('jenis_laporan')->ignore($id_jenis_laporan, 'ID_JENIS_LAPORAN'),
            ],
        ], [
            'JENIS_LAPORAN.required' => 'Kolom jenis laporan wajib diisi.',
            'JENIS_LAPORAN.unique' => 'Jenis laporan ini sudah ada.',
        ]);

        try {
            $jenisLaporan = JenisLaporan::findOrFail($id_jenis_laporan);

            $jenisLaporan->update([
                'JENIS_LAPORAN' => strtoupper($request->JENIS_LAPORAN),
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

    public function destroy($id_jenis_laporan)
    {
        try {
            $jenisLaporan = JenisLaporan::findOrFail($id_jenis_laporan);
            $jenisLaporan->delete();

            return response()->json([
                'success' => true,
                'message' => 'Jenis laporan berhasil dihapus.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data. Kemungkinan data ini masih digunakan.'
            ], 500);
        }
    }
}
