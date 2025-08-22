<?php

namespace App\Services\Humas\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\SSD\Models\KategoriSSD;
use App\Services\SSD\Models\SSD;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Http;
use Illuminate\View\View;
// use App\Services\Humas\Models\Humas;
use Illuminate\Validation\Rule;

class PengaturanSSDController extends Controller {
    public function getPengaturanSSD(Request $request)
    {
        $allKategori = KategoriSSD::orderBy('NAMA_KATEGORI', 'asc')->get();

        $kategoriQuery = KategoriSSD::query();
        if ($request->filled('search_kategori')) {
            $kategoriQuery->where('NAMA_KATEGORI', 'like', '%' . $request->search_kategori . '%');
        }
        $kategoriSsd = $kategoriQuery->orderBy('NAMA_KATEGORI', 'asc')->paginate(10, ['*'], 'kategoriPage')->withQueryString();

        $ssdQuery = SSD::with('kategori');
        if ($request->filled('search_ssd')) {
            $ssdQuery->where('PERTANYAAN_SSD', 'like', '%' . $request->search_ssd . '%');
        }
        $ssds = $ssdQuery->orderBy('PERTANYAAN_SSD', 'asc')->paginate(10, ['*'], 'ssdPage')->withQueryString();

        if ($request->ajax()) {
            return response()->json([
                'kategori_html' => view('Services.Humas.PengaturanSSD.partials.KategoriSSD.tabelBodyKategoriSSD', compact('kategoriSsd'))->render(),
                'kategori_pagination' => (string) $kategoriSsd->links(),
                'ssd_html' => view('Services.Humas.PengaturanSSD.partials.DataSSD.tabelBodyPengaturanSSD', compact('ssds'))->render(),
                'ssd_pagination' => (string) $ssds->links(),
            ]);
        }

        return view('Services.Humas.PengaturanSSD.mainPengaturanSSD', [
            'allKategori' => $allKategori,
            'kategoriSsd' => $kategoriSsd,
            'ssds'        => $ssds
        ]);
    }

    public function storeKategori(Request $request)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:ssd.kategori_ssd,NAMA_KATEGORI'
        ], [
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
            'nama_kategori.unique' => 'Nama kategori ini sudah ada.',
        ]);

        KategoriSSD::create([
            'NAMA_KATEGORI' => $validated['nama_kategori']
        ]);

        return redirect()->route('humas.pengaturan-ssd-humas')->with('success', 'Kategori SSD berhasil ditambahkan.');
    }

    public function storeSsd(Request $request)
    {
        $validated = $request->validate([
            'pertanyaan' => 'required|string|max:255|unique:ssd.ssd,PERTANYAAN_SSD',
            'jawaban' => 'required|string',
            'id_kategori_ssd' => 'required|exists:ssd.kategori_ssd,ID_KATEGORI_SSD'
        ], [
            'pertanyaan.required' => 'Pertanyaan wajib diisi.',
            'pertanyaan.unique' => 'Pertanyaan ini sudah ada.',
            'jawaban.required' => 'Jawaban wajib diisi.',
            'id_kategori_ssd.required' => 'Kategori wajib dipilih.',
            'id_kategori_ssd.exists' => 'Kategori yang dipilih tidak valid.',
        ]);

        SSD::create([
            'PERTANYAAN_SSD' => $validated['pertanyaan'],
            'JAWABAN_SSD' => $validated['jawaban'],
            'ID_KATEGORI_SSD' => $validated['id_kategori_ssd'],
            'STATUS' => '1'
        ]);

        return redirect()->route('humas.pengaturan-ssd-humas')->with('success', 'Data SSD berhasil ditambahkan.');
    }

    public function updateKategori(Request $request, KategoriSSD $kategori)
    {
        $validated = $request->validate([
            'kategori' => ['required', 'string', 'max:255', Rule::unique('ssd.kategori_ssd', 'NAMA_KATEGORI')->ignore($kategori->ID_KATEGORI_SSD, 'ID_KATEGORI_SSD')]
        ], [
            'kategori.required' => 'Nama kategori wajib diisi.',
            'kategori.unique' => 'Nama kategori ini sudah ada.',
        ]);

        $kategori->update([
            'NAMA_KATEGORI' => $validated['kategori']
        ]);

        return redirect()->route('humas.pengaturan-ssd-humas')->with('success', 'Kategori SSD berhasil diperbarui.');
    }

    public function updateSsd(Request $request, SSD $ssd)
    {
        $validated = $request->validate([
            'pertanyaan' => ['required', 'string', 'max:255', Rule::unique('ssd.ssd', 'PERTANYAAN_SSD')->ignore($ssd->ID_SSD, 'ID_SSD')],
            'jawaban' => 'required|string',
            'id_kategori_ssd' => 'required|exists:ssd.kategori_ssd,ID_KATEGORI_SSD'
        ], [
            'pertanyaan.required' => 'Pertanyaan wajib diisi.',
            'pertanyaan.unique' => 'Pertanyaan ini sudah ada.',
            'jawaban.required' => 'Jawaban wajib diisi.',
            'id_kategori_ssd.required' => 'Kategori wajib dipilih.',
        ]);

        $ssd->update([
            'PERTANYAAN_SSD' => $validated['pertanyaan'],
            'JAWABAN_SSD' => $validated['jawaban'],
            'ID_KATEGORI_SSD' => $validated['id_kategori_ssd'],
        ]);

        return redirect()->route('humas.pengaturan-ssd-humas')->with('success', 'Data SSD berhasil diperbarui.');
    }

    public function destroyKategori(KategoriSSD $kategori)
    {
        $kategori->delete();
        return redirect()->route('humas.pengaturan-ssd-humas')->with('success', 'Kategori SSD berhasil dihapus.');
    }

    public function destroySsd(SSD $ssd)
    {
        $ssd->delete();
        return redirect()->route('humas.pengaturan-ssd-humas')->with('success', 'Data SSD berhasil dihapus.');
    }
}
