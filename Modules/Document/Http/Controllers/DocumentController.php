<?php

namespace Modules\Document\Http\Controllers;

use App\Helpers\FileHelper;
use App\Mail\DocumentCreatedMail;
use Modules\Core\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Modules\Customer\Models\Customer;
use Modules\Document\Models\{
    Document,
    DocumentFile,
    DocumentStructure,
    DocumentType,
    Notification
};
use Modules\Employee\Models\Employee;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $query = Document::query();

        if ($request->filled('type')) {
            $query->where('type_id', $request->type);
        }

        if ($request->filled('storage_id')) {
            $query->whereJsonContains('structures', (int)$request->storage_id);
        }

        if ($request->filled('group_id')) {
            $query->whereJsonContains('structures', (int)$request->group_id);
        }

        if ($request->filled('folder_id')) {
            $query->whereJsonContains('structures', (int)$request->folder_id);
        }

        if ($request->filled('book_id')) {
            $query->whereJsonContains('structures', (int)$request->book_id);
        }

        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('code', 'like', '%' . $request->q . '%')
                    ->orWhere('title', 'like', '%' . $request->q . '%');
            });
        }

        $documents = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        $storages = DocumentStructure::where('type', 'storage')->get();
        $groups   = DocumentStructure::where('type', 'group')->get();
        $folders  = DocumentStructure::where('type', 'folder')->get();
        $books    = DocumentStructure::where('type', 'book')->get();

        return view('document::index', compact(
            'documents',
            'storages',
            'groups',
            'folders',
            'books'
        ));
    }


    public function create()
    {
        $storages = DocumentStructure::where('type', 'storage')->get();
        $contentGroups = DocumentStructure::where('type', 'content_group')->get();
        $types = DocumentType::get();
        $folders = DocumentStructure::where('type', 'folder')->get();
        $books = DocumentStructure::where('type', 'book')->get();
        $users = Employee::orderBy('full_name')->get();
        $customers = Customer::orderBy('company_name')->get();

        return view('document::create', compact(
            'storages',
            'contentGroups',
            'folders',
            'books',
            'users',
            'types',
            'customers'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'        => 'required|string|max:255',
            'type_id'      => 'nullable|exists:document_types,id',
            'storage_id'        => 'required|exists:document_structures,id',
            'content_group_id' => 'nullable|exists:document_structures,id',
            'folder_id'        => 'nullable|exists:document_structures,id',
            'book_id'          => 'nullable|exists:document_structures,id',
        ]);

        DB::transaction(function () use ($request) {

            $structures = array_values(array_filter([
                'storage_id'        => $request->storage_id,
                'content_group_id' => $request->content_group_id,
                'folder_id'        => $request->folder_id,
                'book_id'          => $request->book_id,
            ]));

            $data = [
                'title'           => $request->title,
                'type_id'         => $request->type_id,
                'code'            => $request->code,
                'structures'      => $structures,
                'content'         => $request->content,
                'tag'         => $request->tag,
                'bonus'           => $request->bonus,
                'send_mail'       => $request->email_notice ?? 0,
                'issue_date'      => $request->issue_date,
                'effective_date'  => $request->effective_date,
                'expiration_date' => $request->expiration_date,
                'created_at'      => now(),
                'updated_at'      => now(),
            ];

            switch ((int) $request->type_id) {

                case 1:
                    $data['from_unit']  = $request->from_unit_id;
                    $data['recipients']  = $request->recipients
                        ? array_values($request->recipients)
                        : [];

                    $data['to_internals'] = $request->to_internal
                        ? array_values($request->to_internal)
                        : [];

                    $data['followers'] = $request->followers
                        ? array_values($request->followers)
                        : [];
                    break;

                case 2:
                    $data['aa']            = $request->input('sender.aa');
                    $data['from_unit']  = Auth::user()->employee_id;
                    $data['contract_type'] = $request->input('sender.contract_type');
                    $data['sender']        = $request->sender ?? [];
                    $data['receivers']     = $request->receivers
                        ? array_values($request->receivers)
                        : [];
                    break;

                case 3:
                    $data['aa']            = $request->aa;
                    $data['from_unit']  = Auth::user()->employee_id;
                    $data['contract_type'] = $request->contract_type;
                    $data['sender']        = $request->sender ?? [];
                    $data['receivers']     = $request->receivers
                        ? array_values($request->receivers)
                        : [];
                    break;
            }

            $document = Document::create($data);
            $fromUserId = Auth::user()->employee_id;

            $relatedUserIds = collect([
                ...($data['to_internals'] ?? []),
                ...($data['receivers'] ?? []),
                ...($data['followers'] ?? []),
            ])->unique()->filter();

            foreach ($relatedUserIds as $uid) {
                Notification::create([
                    'from_user' => $fromUserId,
                    'to_user'   => $uid,
                    'type'      => 'document_created',
                    'title'     => 'Văn bản mới',
                    'content'   => Auth::user()->employee->full_name . ' đã tạo văn bản: ' . $document->title,
                    'url'       => route('document.show', $document->id),
                ]);
            }

            if ($document->send_mail) {
                foreach ($relatedUserIds as $uid) {
                    $user = Employee::find($uid);

                    if ($user?->email_work) {
                        Mail::to($user->email_workl)->queue(
                            new DocumentCreatedMail($document, Auth::user()->employee)
                        );
                    }
                }
            }

            if (!empty($request['files'])) {
                foreach ($request['files'] as $file) {
                    $path = 'document/' . str_replace('/', '-',  $document->code);
                    $file = FileHelper::uploadFile($file, $path);
                    DocumentFile::create([
                        'document_id' => $document->id,
                        'user_id' => Auth::user()->employee_id,
                        'file_path' => $file['path'],
                        'extension' => $file['extension'],
                    ]);
                }
            }
        });

        return redirect()
            ->route('document.index')
            ->with('success', 'Tạo văn bản thành công');
    }

    public function show($id)
    {
        $document = Document::findOrFail($id);

        $storages = DocumentStructure::where('type', 'storage')->get();
        $contentGroups = DocumentStructure::where('type', 'content_group')->get();
        $folders = DocumentStructure::where('type', 'folder')->get();
        $books = DocumentStructure::where('type', 'book')->get();
        $users = Employee::orderBy('full_name')->get();
        $customers = Customer::orderBy('company_name')->get();

        return view('document::show', compact(
            'document',
            'storages',
            'contentGroups',
            'folders',
            'books',
            'users',
            'customers'
        ));
    }

    public function edit($id)
    {
        $document = Document::findOrFail($id);

        return view('document::edit', [
            'document'  => $document,
            'users'     => Employee::all(),
            'customers' => Customer::all(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $document = Document::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'storage_id' => 'required|exists:document_structures,id',
        ]);

        DB::transaction(function () use ($request, $document) {

            $structures = array_values(array_filter([
                $request->storage_id,
                $request->content_group_id,
                $request->folder_id,
                $request->book_id,
            ]));

            $data = [
                'title'           => $request->title,
                'content'         => $request->content,
                'bonus'           => $request->bonus,
                'structures'      => $structures,
                'issue_date'      => $request->issue_date,
                'effective_date'  => $request->effective_date,
                'expiration_date' => $request->expiration_date,
                'send_mail'       => $request->email_notice ?? 0,
            ];

            switch ((int)$document->type_id) {
                case 1:
                    $data['from_unit']    = $request->from_unit_id;
                    $data['recipients']   = array_values($request->recipients ?? []);
                    $data['to_internals'] = array_values($request->to_internal ?? []);
                    $data['followers']    = array_values($request->followers ?? []);
                    break;

                case 2:
                case 3:
                    $data['aa']            = $request->aa ?? $request->input('sender.aa');
                    $data['contract_type'] = $request->contract_type ?? $request->input('sender.contract_type');
                    $data['sender']        = $request->sender ?? [];
                    $data['receivers']     = array_values($request->receivers ?? []);
                    break;
            }

            $document->update($data);
        });

        return redirect()
            ->route('document.show', $document->id)
            ->with('success', 'Cập nhật văn bản thành công');
    }

    public function approve($id)
    {

        $user = Auth::user();
        $document = Document::findOrFail($id);

        $document->update([
            'status' => 'approved',
            'approved_by' => $user->employee_id,
        ]);

        return back()->with('success', 'Đã duyệt yêu cầu nghỉ.');
    }
}
