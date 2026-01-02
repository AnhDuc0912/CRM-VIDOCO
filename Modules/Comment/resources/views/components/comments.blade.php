@use('Modules\Employee\Enums\EmployeeFileTypeEnum')
@use('App\Helpers\FileHelper')
<li class="d-flex mb-3">
    <!-- Avatar -->
    <img src="{{ $comment->user?->avatar ? FileHelper::getFileUrl($comment->user?->avatar) : asset('assets/images/avatars/avatar-1.png') }}"
        class="rounded-circle me-2" width="38" height="38">

    <!-- Nội dung -->
    <div class="flex-grow-1">
        <div class="p-3 bg-light-subtle border rounded-4 shadow-sm">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-2">
                <strong class="text-dark fs-6">{{ $comment->user->full_name ?? $comment->name }}</strong>
                <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
            </div>

            <!-- Nội dung -->
            <p class="mb-2 fs-6 text-body">{{ $comment->content }}</p>

            <!-- Nút Reply -->
            <button class="btn btn-sm btn-danger py-0 px-2 reply-btn" data-id="{{ $comment->id }}">
                <i class="bi bi-reply-fill me-1"></i> Trả lời
            </button>
        </div>

        <!-- Form Reply -->
        <form action="{{ route('comment.store') }}" method="POST" class="d-flex mt-2 reply-form d-none"
            id="reply-form-{{ $comment->id }}">
            @csrf
            <input type="hidden" name="commentable_id" value="{{ $comment->commentable_id }}">
            <input type="hidden" name="commentable_type" value="{{ $comment->commentable_type }}">
            <input type="hidden" name="parent_id" value="{{ $comment->id }}">

            <img src="{{ auth()->user()->employee?->avatar ? FileHelper::getFileUrl(auth()->user()->employee?->avatar) : asset('assets/images/avatars/avatar-1.png') }}"
                class="rounded-circle me-2" width="30" height="30">
            <input type="text" name="content" class="form-control form-control-sm" placeholder="Viết phản hồi...">
            <button class="btn btn-sm btn-success ms-2 px-3">Gửi</button>
        </form>

        <!-- Replies -->
        @if ($comment->replies->count())
            <ul class="list-unstyled ms-4 mt-3">
                @foreach ($comment->replies as $reply)
                    @include('comment::components.comments', ['comment' => $reply])
                @endforeach
            </ul>
        @endif
    </div>
</li>
