<?php

namespace App\Services\Humas\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Ticketing\Models\UnitKerja;
use App\Services\Ticketing\Models\UserComplaint;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class UnitKerjaHumasController extends Controller {
    public function getUnitKerjaHumas(Request $request){
        $allUnitKerja = UnitKerja::orderBy('ID_BAGIAN')->get();
        $topLevelIDs = ['A', 'B', 'C', 'D', 'E'];

        $topLevelParentsCollection = $allUnitKerja->whereIn('ID_BAGIAN', $topLevelIDs);

        $unitsForDropdown = $allUnitKerja->filter(function ($unit) {
            return strlen($unit->ID_BAGIAN) > 1;
        });

        $perPage = 10;
        $currentPage = $request->input('page', 1);
        $currentPageItems = $topLevelParentsCollection->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $paginatedParents = new LengthAwarePaginator(
            $currentPageItems,
            $topLevelParentsCollection->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );


        $groupedChildren = $allUnitKerja->groupBy('ID_PARENT_BAGIAN');
        $promotedIDs = ['B', 'C', 'D', 'E'];
        if (isset($groupedChildren['A'])) {
            $groupedChildren['A'] = $groupedChildren['A']->whereNotIn('ID_BAGIAN', $promotedIDs);
        }

        $adminQuery = UserComplaint::with('unitKerja');
        if ($request->filled('filter_unit')) {
            $adminQuery->where('ID_BAGIAN', $request->filter_unit);
        }
        if ($request->filled('filter_status')) {
            $adminQuery->where('VALIDASI', $request->filter_status);
        }
        $admins = $adminQuery->latest('TGL_INSROW')->paginate(10, ['*'], 'admin_page');

        return view('Services.Humas.unitKerjaHumas.mainUnitKerja', [
            'parents' => $paginatedParents,
            'children' => $groupedChildren,
            'allUnits' => $allUnitKerja,
            'unitsForDropdown' => $unitsForDropdown,
            'admins' => $admins
        ]);
    }

    public function storeUnitKerja(Request $request)
    {
        $request->validate([
            'id_parent_bagian' => 'required|string',
            'NAMA_BAGIAN' => 'required|string|max:255',
            'NAMA_BAGIAN_SINGULAR' => 'nullable|string|max:255',
            'NAMA_ALTERNATIF' => 'nullable|string|max:255',
            'STATUS' => 'required|boolean',
        ]);
        $parentId = $request->input('id_parent_bagian');

        $lastChild = UnitKerja::where('ID_PARENT_BAGIAN', $parentId)
                               ->orderBy('ID_BAGIAN', 'desc')
                               ->first();

        $newIdBagian = '';
        if ($lastChild) {
            $lastNumber = (int) substr($lastChild->ID_BAGIAN, strlen($parentId));
            $newNumber = $lastNumber + 1;
            $newIdBagian = $parentId . sprintf('%02d', $newNumber);
        } else {
            $newIdBagian = $parentId . '01';
        }

        $dataToCreate = [
            'ID_BAGIAN' => $newIdBagian,
            'NAMA_BAGIAN' => $request->input('NAMA_BAGIAN'),
            'NAMA_BAGIAN_SINGULAR' => $request->input('NAMA_BAGIAN_SINGULAR') ?? $request->input('NAMA_BAGIAN'),
            'NAMA_ALTERNATIF' => $request->input('NAMA_ALTERNATIF'),
            'ID_PARENT_BAGIAN' => $parentId,
            'SUPER' => 0,
            'STATUS' => $request->input('STATUS'),
            'TGL_INSROW' => now(),
        ];

        UnitKerja::create($dataToCreate);

        return redirect()->route('humas.unit-kerja-humas')->with('success', 'Unit kerja baru berhasil ditambahkan!');
    }

    public function updateUnitKerja(Request $request, UnitKerja $unitKerja)
    {
        $request->validate([
            'NAMA_BAGIAN' => 'required|string|max:255',
            'NAMA_BAGIAN_SINGULAR' => 'nullable|string|max:255',
            'NAMA_ALTERNATIF' => 'nullable|string|max:255',
            'STATUS' => 'required|boolean',
        ]);

        $dataToUpdate = [
            'NAMA_BAGIAN' => $request->input('NAMA_BAGIAN'),
            'NAMA_BAGIAN_SINGULAR' => $request->input('NAMA_BAGIAN_SINGULAR') ?? $request->input('NAMA_BAGIAN'),
            'NAMA_ALTERNATIF' => $request->input('NAMA_ALTERNATIF'),
            'STATUS' => $request->input('STATUS'),
            'TGL_UPDATE' => now(),
        ];

        $unitKerja->update($dataToUpdate);

        return redirect()->route('humas.unit-kerja-humas')->with('success', 'Unit kerja berhasil diperbarui!');
    }

    public function destroyUnitKerja(UnitKerja $unitKerja)
    {
        $childCount = UnitKerja::where('ID_PARENT_BAGIAN', $unitKerja->ID_BAGIAN)->count();

        if ($childCount > 0) {
            return redirect()->route('humas.unit-kerja-humas')
                             ->with('error', 'Gagal! Unit kerja "' . $unitKerja->NAMA_BAGIAN . '" tidak dapat dihapus karena memiliki ' . $childCount . ' sub bagian.');
        }

        $unitKerja->delete();

        return redirect()->route('humas.unit-kerja-humas')
                         ->with('success', 'Unit kerja "' . $unitKerja->NAMA_BAGIAN . '" berhasil dihapus.');
    }
}
