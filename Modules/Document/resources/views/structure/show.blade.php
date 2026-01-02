@extends('core::layouts.app')
@section('title', 'Chi tiết văn bản')
@section('content')
    <div class="card">
        <div class="card-body">
            <h3>{{ $document->title }}</h3>
            <p><strong>Loại:</strong> {{ $document->type?->name }}</p>
            <p><strong>Kho:</strong> {{ $document->category?->name }}</p>
            <p><strong>Người gửi:</strong> {{ $document->sender_external ?: $document->sender_id }}</p>
            <p><strong>Hiệu lực:</strong> {{ $document->effective_date?->format('d/m/Y') }} -
                {{ $document->expiration_date?->format('d/m/Y') }}</p>
            <hr>
            <div>{!! nl2br(e($document->content)) !!}</div>

            <h5 class="mt-4">Người nhận</h5>
            <ul>
                @foreach ($document->recipients as $r)
                    <li>{{ $r->external ?: $r->user?->name ?? $r->user_id }}</li>
                @endforeach
            </ul>
            <h5 class="mt-4">Người theo dõi</h5>
            <ul>
                @foreach ($document->followers as $f)
                    <li>{{ $f->user?->name ?? $f->user_id }}</li>
                @endforeach
            </ul>
            <h5 class="mt-4">File đính kèm</h5>
            <ul>
                @foreach ($document->files as $file)
                    <li><a href=\"{{ Storage::url($file->path) }}\" target=\"_blank\">{{ $file->filename }}</a></li>
                @endforeach
            </ul>

        </div>
    </div>
@endsection
