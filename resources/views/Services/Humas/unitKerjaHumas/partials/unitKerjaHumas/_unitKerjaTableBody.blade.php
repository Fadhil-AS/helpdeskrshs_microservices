@forelse ($parents as $parent)
    @include('Services.Humas.unitKerjaHumas.partials.unitKerjaHumas._unitKerjaRow', [
        'unit' => $parent,
        'children' => $children,
        'level' => 0,
        'search' => $search ?? null,
    ])
@empty
    <tr>
        <td colspan="7" class="text-center">Data tidak ditemukan.</td>
    </tr>
@endforelse
