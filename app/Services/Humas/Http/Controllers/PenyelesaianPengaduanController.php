<?php

namespace App\Services\Humas\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Ticketing\Models\PenyelesaianPengaduan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

class PenyelesaianPengaduanController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'PENYELESAIAN_PENGADUAN' => 'required|string|max:100|unique:penyelesaian_pengaduan',
        ], [
            'PENYELESAIAN_PENGADUAN.required' => 'Kolom penyelesaian pengaduan wajib diisi.',
            'PENYELESAIAN_PENGADUAN.unique' => 'Jenis penyelesaian ini sudah ada.',
        ]);

        try {
            DB::beginTransaction();
            $today = Carbon::now()->format('Ymd');
            $lastRecord = PenyelesaianPengaduan::where('ID_PENYELESAIAN', 'LIKE', $today . '%')
                ->latest('ID_PENYELESAIAN')
                ->first();

            $sequence = $lastRecord ? (int)substr($lastRecord->ID_PENYELESAIAN, -6) + 1 : 1;
            $newId = $today . str_pad($sequence, 6, '0', STR_PAD_LEFT);

            PenyelesaianPengaduan::create([
                'ID_PENYELESAIAN' => $newId,
                'PENYELESAIAN_PENGADUAN' => strtoupper($request->PENYELESAIAN_PENGADUAN),
                'STATUS' => 1,
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Penyelesaian pengaduan berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Gagal menambahkan data: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id_penyelesaian)
    {
        $request->validate([
            'PENYELESAIAN_PENGADUAN' => [
                'required',
                'string',
                'max:100',
                Rule::unique('penyelesaian_pengaduan')->ignore($id_penyelesaian, 'ID_PENYELESAIAN'),
            ],
        ], [
            'PENYELESAIAN_PENGADUAN.required' => 'Kolom penyelesaian pengaduan wajib diisi.',
            'PENYELESAIAN_PENGADUAN.unique' => 'Jenis penyelesaian ini sudah ada.',
        ]);

        try {
            $penyelesaian = PenyelesaianPengaduan::findOrFail($id_penyelesaian);

            $penyelesaian->update([
                'PENYELESAIAN_PENGADUAN' => strtoupper($request->PENYELESAIAN_PENGADUAN),
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

    public function destroy($id_penyelesaian)
    {
        try {
            $penyelesaian = PenyelesaianPengaduan::findOrFail($id_penyelesaian);

            $penyelesaian->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data. Kemungkinan data ini masih digunakan.'
            ], 500);
        }
    }
}
