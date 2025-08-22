<tr class="parent-row child-row group-{{ $unit->ID_PARENT_BAGIAN }}" data-child="group-{{ $unit->ID_BAGIAN }}"
    data-level="{{ $level }}" @if ($level > 0) style="display: none;" @endif>
    <td style="cursor: pointer;">
        <span style="padding-left: {{ $level * 25 }}px;">
            @if (isset($children[$unit->ID_BAGIAN]) && $children[$unit->ID_BAGIAN]->isNotEmpty())
                <span class="toggle-icon">▸</span>
            @else
                <span class="toggle-icon" style="opacity: 0; cursor: default;">▸</span>
            @endif
            <strong>{{ $unit->ID_BAGIAN }}</strong>
        </span>
    </td>

    <td>{{ $unit->NAMA_BAGIAN }}</td>
    <td>{{ $unit->NAMA_BAGIAN_SINGULAR }}</td>
    <td>{{ $unit->NAMA_ALTERNATIF ?? '-' }}</td>
    <td>
        @if ($unit->STATUS == 1)
            <span class="badge bg-success">Aktif</span>
        @else
            <span class="badge bg-secondary">Tidak Aktif</span>
        @endif
    </td>
    <td>{{ \Carbon\Carbon::parse($unit->TGL_INSROW)->locale('id')->isoFormat('DD MMMM YYYY') }}</td>
    <td>
        <a href="#" data-bs-toggle="modal" data-bs-target="#modalEditUnitKerja"
            data-unit='{{ json_encode($unit) }}' onclick="event.stopPropagation()">
            <i class="bi bi-pencil-square me-2"></i>
        </a>
        <a href="#" class="hapus-unit-btn text-danger" data-id="{{ $unit->ID_BAGIAN }}"
            data-nama="{{ $unit->NAMA_BAGIAN }}" data-admin-count="{{ $unit->admins_count }}"
            data-child-count="{{ $unit->children_count }}">
            <i class="bi bi-trash"></i>
        </a>
    </td>
</tr>

@if (isset($children[$unit->ID_BAGIAN]))
    @foreach ($children[$unit->ID_BAGIAN] as $child)
        @include('Services.Humas.unitKerjaHumas.partials.unitKerjaHumas._unitKerjaRow', [
            'unit' => $child,
            'children' => $children,
            'level' => $level + 1,
        ])
    @endforeach
@endif
