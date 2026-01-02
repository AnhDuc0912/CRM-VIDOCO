<?php

namespace Modules\Document\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DocumentDatabaseSeeder extends Seeder
{
    public function run()
    {
        $users = DB::table('users')->pluck('id')->toArray();
        $storages = DB::table('storages')->pluck('id')->toArray();
        $folders = DB::table('folders')->pluck('id')->toArray();
        $books = DB::table('books')->pluck('id')->toArray();
        $units = DB::table('units')->pluck('id')->toArray();
        $departments = DB::table('departments')->pluck('id')->toArray();
        $categories = DB::table('document_categories')->pluck('id')->toArray();
        $types = DB::table('document_types')->pluck('id')->toArray();

        $now = Carbon::now();

        // Tạo 1 văn bản demo
        $documentId = DB::table('documents')->insertGetId([
            'title' => 'Văn bản demo',
            'type_id' => $types[array_rand($types)],
            'category_id' => $categories[array_rand($categories)],
            'code' => 'BOOK1-TYPE1-' . $now->format('Ymd') . '-001',
            'storage_id' => $storages[array_rand($storages)],
            'content_group_id' => null,
            'folder_id' => $folders[array_rand($folders)],
            'book_id' => $books[array_rand($books)],

            'from_unit_id' => $units[array_rand($units)],
            'to_internal' => json_encode(array_slice($users, 0, 2)),
            'to_external' => 'external@example.com',
            'department_id' => $departments[array_rand($departments)],
            'distribute_internal' => json_encode(array_slice($users, 1, 2)),
            'sent_from_source' => 'Nguồn gửi thử nghiệm',

            'content' => 'Đây là nội dung demo của văn bản.',
            'labels' => 'demo, test',
            'signer_position' => 'Trưởng phòng',
            'signer_id' => $users[array_rand($users)],
            'main_handler_id' => $users[array_rand($users)],

            'release_date' => $now->format('Y-m-d'),
            'issue_date' => $now->format('Y-m-d'),
            'effective_date' => $now->format('Y-m-d'),
            'expiration_date' => $now->addDays(30)->format('Y-m-d'),

            'is_urgent' => true,
            'is_confidential' => false,
            'allow_recall' => true,
            'internal_publish' => true,
            'show_on_dashboard' => true,
            'allow_feedback' => true,

            'has_signature' => true,
            'email_notice' => true,

            'view_permission' => json_encode(['user_' . $users[0], 'user_' . $users[1]]),
            'edit_permission' => json_encode(['user_' . $users[2]]),

            'created_by' => $users[array_rand($users)],
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // Recipients
        DB::table('document_recipients')->insert([
            [
                'document_id' => $documentId,
                'user_id' => $users[0],
                'external' => null,
                'is_cc' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'document_id' => $documentId,
                'user_id' => null,
                'external' => 'partner@example.com',
                'is_cc' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        ]);

        // Followers
        foreach (array_slice($users, 0, 2) as $userId) {
            DB::table('document_followers')->insert([
                'document_id' => $documentId,
                'user_id' => $userId,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // Approvals
        foreach (array_slice($users, 0, 2) as $step => $approverId) {
            DB::table('document_approvals')->insert([
                'document_id' => $documentId,
                'approver_id' => $approverId,
                'step' => $step + 1,
                'approved' => null,
                'comment' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // Files
        DB::table('document_files')->insert([
            [
                'document_id' => $documentId,
                'filename' => 'file_demo.pdf',
                'path' => 'uploads/file_demo.pdf',
                'mime' => 'application/pdf',
                'size' => 1024,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        ]);

        // Logs
        DB::table('document_logs')->insert([
            [
                'document_id' => $documentId,
                'user_id' => $users[0],
                'action' => 'Tạo văn bản',
                'meta' => json_encode(['title' => 'Văn bản demo']),
                'created_at' => $now,
                'updated_at' => $now,
            ]
        ]);

        $this->command->info('Seed demo văn bản đã tạo thành công: ID=' . $documentId);
    }
}
