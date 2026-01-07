@extends('core::layouts.app')

@section('title', 'Cấu trúc văn bản')

@section('content')
    <div class="container-fluid page-content">

        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Quản lý Cấu Trúc</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Cấu trúc văn bản</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <div class="btn-group">
                    <a href="{{ route('document.structure.create') }}" class="btn btn-secondary m-1">
                        <i class="bx bx-cloud-upload me-1"></i>Thêm Cấu Trúc
                    </a>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table table-striped table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="50">ID</th>
                            <th>Tên</th>
                            <th width="160">Loại</th>

                            <th width="140">Thao tác</th>
                        </tr>
                    </thead>
                    @php
                        $byType = [];
                        foreach ($structures as $st) {
                            $byType[$st->type][] = $st;
                        }
                        $typeOrder = array_keys(\Modules\Document\Models\DocumentStructure::TYPE_LABELS);
                        $extraTypes = array_diff(array_keys($byType), $typeOrder);
                    @endphp

                    <tbody style="border-top:none!important;">
                        @php $hasAny = false; @endphp
                        @foreach($typeOrder as $type)
                            @if(!empty($byType[$type]))
                                @php $hasAny = true; @endphp
                                <tr class="table-secondary">
                                    <td colspan="4"><strong>{{ $typeLabels[$type] ?? $type }}</strong></td>
                                </tr>
                                @php
                                    $itemsByParent = [];
                                    foreach ($byType[$type] as $st) {
                                        $itemsByParent[$st->parent_id][] = $st;
                                    }
                                @endphp

                                @if(!empty($itemsByParent[null]) || !empty($itemsByParent['']))
                                    @foreach($itemsByParent[null] ?? ($itemsByParent[''] ?? []) as $s)
                                        @include('document::structure._row', ['s' => $s, 'itemsByParent' => $itemsByParent, 'depth' => 0, 'typeLabels' => $typeLabels])
                                    @endforeach
                                @else
                                    @foreach($byType[$type] as $s)
                                        @include('document::structure._row', ['s' => $s, 'itemsByParent' => $itemsByParent ?? [], 'depth' => 0, 'typeLabels' => $typeLabels])
                                    @endforeach
                                @endif
                            @endif
                        @endforeach

                        @foreach($extraTypes as $type)
                            @if(!empty($byType[$type]))
                                @php $hasAny = true; @endphp
                                <tr class="table-secondary">
                                    <td colspan="4"><strong>{{ $typeLabels[$type] ?? $type }}</strong></td>
                                </tr>
                                @php
                                    $itemsByParent = [];
                                    foreach ($byType[$type] as $st) {
                                        $itemsByParent[$st->parent_id][] = $st;
                                    }
                                @endphp
                                @if(!empty($itemsByParent[null]) || !empty($itemsByParent['']))
                                    @foreach($itemsByParent[null] ?? ($itemsByParent[''] ?? []) as $s)
                                        @include('document::structure._row', ['s' => $s, 'itemsByParent' => $itemsByParent, 'depth' => 0, 'typeLabels' => $typeLabels])
                                    @endforeach
                                @else
                                    @foreach($byType[$type] as $s)
                                        @include('document::structure._row', ['s' => $s, 'itemsByParent' => $itemsByParent ?? [], 'depth' => 0, 'typeLabels' => $typeLabels])
                                    @endforeach
                                @endif
                            @endif
                        @endforeach

                        @unless($hasAny)
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">Chưa có cấu trúc</td>
                            </tr>
                        @endunless
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
