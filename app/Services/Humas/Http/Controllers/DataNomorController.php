<?php

namespace App\Services\Humas\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;
use App\Services\Humas\Models\Humas;
use Illuminate\Validation\Rule;

class DataNomorController extends Controller {
    public function getDataNomor(): View
    {
        $dataHumas = Humas::all();
        return view('Services.Humas.DataNomor.mainDataNomor', compact('dataHumas'));
    }

    public function updateNomor(Request $request, Humas $humas)
    {
        $validatedField = $request->validate([
            'field_to_update' => ['required', Rule::in(['no_tlpn_humas', 'no_tlpn_rshs'])]
        ]);

        $field = $validatedField['field_to_update'];
        $currentValue = $humas->$field;

        $request->validate([
            'no_tlpn' => [
                'required',
                'string',
                'numeric',
                'digits_between:10,15',
                Rule::notIn([$currentValue]),
            ],
        ], [
            'no_tlpn.required' => 'Nomor baru wajib diisi.',
            'no_tlpn.numeric' => 'Nomor harus berupa angka.',
            'no_tlpn.digits_between' => 'Nomor harus terdiri dari 10 hingga 15 digit.',
            'no_tlpn.not_in' => 'Nomor baru tidak boleh sama dengan nomor saat ini.',
        ]);

        $humas->update([
            $field => $request->no_tlpn
        ]);

        $namaData = '';
        if ($field === 'no_tlpn_humas') {
            $namaData = 'Nomor Humas';
        } elseif ($field === 'no_tlpn_rshs') {
            $namaData = 'Nomor RSHS';
        }

        return redirect()->back()->with('success', $namaData . ' berhasil diperbarui.');
    }
}
