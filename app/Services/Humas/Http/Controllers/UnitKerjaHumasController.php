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
        $paginatedParents = null;
        $groupedChildren = collect();
        $searchResults = null;
        $promotedIDs = ['B', 'C', 'D', 'E'];
        $allUnitKerja = UnitKerja::orderBy('ID_BAGIAN')->get();

        if ($request->filled('search')) {
            $search = strtolower($request->search);

            $filtered = $allUnitKerja->filter(function ($unit) use ($search) {
                return str_contains(strtolower($unit->NAMA_BAGIAN), $search) ||
                       str_contains(strtolower($unit->NAMA_BAGIAN_SINGULAR), $search) ||
                       str_contains(strtolower($unit->NAMA_ALTERNATIF), $search);
            });
            $perPage = 15;
            $currentPage = $request->input('page', 1);
            $currentPageItems = $filtered->slice(($currentPage - 1) * $perPage, $perPage)->values();

            $searchResults = new LengthAwarePaginator(
                $currentPageItems,
                $filtered->count(),
                $perPage,
                $currentPage,
                ['path' => $request->url(), 'query' => $request->query()]
            );

        } else {
            $topLevelIDs = ['A', 'B', 'C', 'D', 'E'];
            $topLevelParentsCollection = $allUnitKerja->whereIn('ID_BAGIAN', $topLevelIDs);

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
        }

        $unitsForDropdown = $allUnitKerja->filter(fn($unit) => strlen($unit->ID_BAGIAN) > 1);
        $adminQuery = UserComplaint::with('unitKerja');
        if ($request->filled('filter_unit')) $adminQuery->where('ID_BAGIAN', $request->filter_unit);
        if ($request->filled('filter_status')) $adminQuery->where('VALIDASI', $request->filter_status);
        if ($request->filled('search_admin')) {
            $searchAdmin = $request->search_admin;
            $adminQuery->where(function ($query) use ($searchAdmin) {
                $query->where('NAME', 'like', '%' . $searchAdmin . '%')
                      ->orWhere('USERNAME', 'like', '%' . $searchAdmin . '%')
                      ->orWhereHas('unitKerja', function ($q) use ($searchAdmin) {
                            $q->where('NAMA_BAGIAN', 'like', '%' . $searchAdmin . '%');
                        });
            });
        }

        $admins = $adminQuery->latest('TGL_INSROW')->paginate(10, ['*'], 'admin_page');


        if ($request->ajax()) {
            if ($request->has('search_admin')) {
                return view('Services.Humas.unitKerjaHumas.partials.adminUKH.contentTabel', compact('admins'))->render();
            }
            return view('Services.Humas.unitKerjaHumas.partials.unitKerjaHumas.tabelUKH', compact(
                'paginatedParents', 'groupedChildren', 'searchResults', 'promotedIDs'
            ))->render();
        }


        return view('Services.Humas.unitKerjaHumas.mainUnitKerja', compact(
            'paginatedParents',
            'groupedChildren',
            'searchResults',
            'allUnitKerja',
            'unitsForDropdown',
            'admins',
            'promotedIDs'
        ));
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
        $promotedIDs = ['B', 'C', 'D', 'E'];

        $lastChild = UnitKerja::where('ID_PARENT_BAGIAN', $parentId)
                                ->whereNotIn('ID_BAGIAN', $promotedIDs)
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
