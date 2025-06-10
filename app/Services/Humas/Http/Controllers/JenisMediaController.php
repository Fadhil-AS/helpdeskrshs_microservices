<?php

namespace App\Services\Humas\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Ticketing\Models\JenisMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

class JenisMediaController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'JENIS_MEDIA' => 'required|string|max:100|unique:jenis_media',
        ], [
            'JENIS_MEDIA.required' => 'Kolom jenis media wajib diisi.',
            'JENIS_MEDIA.unique' => 'Jenis media ini sudah ada.',
        ]);

        try {
            DB::beginTransaction();

            $today = Carbon::now()->format('Ymd');
            $lastRecord = JenisMedia::where('ID_JENIS_MEDIA', 'LIKE', $today . '%')
                ->latest('ID_JENIS_MEDIA')
                ->first();

            $sequence = $lastRecord ? (int)substr($lastRecord->ID_JENIS_MEDIA, -6) + 1 : 1;
            $newId = $today . str_pad($sequence, 6, '0', STR_PAD_LEFT);

            JenisMedia::create([
                'ID_JENIS_MEDIA' => $newId,
                'JENIS_MEDIA' => strtoupper($request->JENIS_MEDIA),
                'STATUS' => 1,
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Jenis media berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Gagal menambahkan data: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id_jenis_media)
    {
        $request->validate([
            'JENIS_MEDIA' => [
                'required',
                'string',
                'max:100',
                Rule::unique('jenis_media')->ignore($id_jenis_media, 'ID_JENIS_MEDIA'),
            ],
        ], [
            'JENIS_MEDIA.required' => 'Kolom jenis media wajib diisi.',
            'JENIS_MEDIA.unique' => 'Jenis media ini sudah ada.',
        ]);

        try {
            $jenisMedia = JenisMedia::findOrFail($id_jenis_media);

            $jenisMedia->update([
                'JENIS_MEDIA' => strtoupper($request->JENIS_MEDIA),
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

    public function destroy($id_jenis_media)
    {
        try {
            $jenisMedia = JenisMedia::findOrFail($id_jenis_media);
            $jenisMedia->delete();

            return response()->json([
                'success' => true,
                'message' => 'Jenis media berhasil dihapus.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data. Kemungkinan data ini masih digunakan di tempat lain.'
            ], 500);
        }
    }
}
