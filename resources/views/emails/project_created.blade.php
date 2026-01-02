<div style="padding:15px;border:1px solid #ddd;border-radius:6px;">
    <h3 style="margin-top:0;">DỰ ÁN ĐÃ ĐƯỢC KHỞI TẠO</h3>
    <p><strong>Tên dự án:</strong> {{ $project->project_name }}</p>
    <p><strong>Ngày bắt đầu:</strong> {{ $project->start_date }}</p>
    <p><strong>Ngày kết thúc dự kiến:</strong> {{ $project->end_date }}</p>
    <p><strong>Người quản lý:</strong> {{ $managerName }}</p>
    <p><strong>Nhân viên được giao:</strong> {{ $assigneesNames ?: 'Không có' }}</p>
</div>
