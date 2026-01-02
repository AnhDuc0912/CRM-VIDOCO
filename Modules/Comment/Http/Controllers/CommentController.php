<?php

namespace Modules\Comment\Http\Controllers;

use Modules\Core\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Comment\Models\Comment;
use Modules\Document\Models\Notification;
use Modules\Employee\Models\Employee;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        set_breadcrumbs([
            [
                'title' => 'Danh sách bình luận',
                'url' => null,
            ],
        ]);

        $comments = Comment::get();

        return view('comment::index', compact('comments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('comment::create');
    }

    /**
     * Store a newly created resource in storage.
     */

  public function store(Request $request)
{
    // 1. Lấy user được mention (GIỮ NGUYÊN)
    preg_match_all('/data-user-id="(\d+)"/', $request->content, $matches);
    $userIds = array_unique($matches[1] ?? []);
    // 2. CLEAN HTML at.js (điểm mấu chốt)
    $cleanContent = preg_replace(
        '/<span[^>]*class="mention"[^>]*>(@[^<]+)<\/span>/',
        '$1',
        $request->content
    );

    // xoá wrapper atwho-inserted + &nbsp;
    $cleanContent = strip_tags($cleanContent);
    $cleanContent = html_entity_decode($cleanContent);
    $cleanContent = trim(preg_replace('/\s+/', ' ', $cleanContent));

    // 3. Lưu comment
    $comment = Comment::create([
        'user_id'          => Auth::user()->employee_id,
        'content'          => $cleanContent,
        'commentable_id'   => $request->commentable_id,
        'commentable_type' => $request->commentable_type,
        'parent_id'        => $request->parent_id,
    ]);

    // 4. Tạo notification
    foreach ($userIds as $toUserId) {
        

        Notification::create([
            'from_user' => Auth::user()->employee_id,
            'to_user'   => $toUserId,
            'notifiable_id'   => $comment->id,
            'notifiable_type' => Comment::class,
            'title'   => 'Bạn được nhắc trong bình luận',
            'content' => $comment->content,
            'url'     => route('project.show', $request->commentable_id),
            'type'    => 'mention_comment',
        ]);
    }

    return back()->with('success', 'Bình luận thành công');
}




    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('comment::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('comment::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}


    public function searchEmployee(Request $request)
    {
        return Employee::where('full_name', 'like', '%' . $request->q . '%')
            ->limit(10)
            ->get()
            ->map(fn($u) => [
                'id'   => $u->id,
                'name' => $u->full_name,
            ])
            ->values();
    }
}
