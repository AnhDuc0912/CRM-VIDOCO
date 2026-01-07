<tr>
    <td>{{ $s->id }}</td>
    <td style="padding-left: {{ $depth * 20 }}px; white-space: nowrap;">
        @if(isset($itemsByParent[$s->id]))
            <i class="bx bx-folder me-1"></i>
        @else
            <i class="bx bx-file me-1"></i>
        @endif
        {{ $s->name }}
    </td>
    <td>
        <span class="badge bg-info">{{ $typeLabels[$s->type] ?? $s->type }}</span>
    </td>
    <td>
        <a href="{{ route('document.structure.edit', $s->id) }}" class="btn btn-sm btn-warning">
            <i class="bx bx-edit"></i>
        </a>

        <form action="{{ route('document.structure.delete', $s->id) }}" method="POST" class="d-inline">
            @csrf @method('DELETE')
            <button onclick="return confirm('Xóa cấu trúc này?')" class="btn btn-sm btn-danger">
                <i class="bx bx-trash"></i>
            </button>
        </form>
    </td>
</tr>

@if(isset($itemsByParent[$s->id]))
    @foreach($itemsByParent[$s->id] as $child)
        @include('document::structure._row', ['s' => $child, 'itemsByParent' => $itemsByParent, 'depth' => $depth + 1, 'typeLabels' => $typeLabels])
    @endforeach
@endif
