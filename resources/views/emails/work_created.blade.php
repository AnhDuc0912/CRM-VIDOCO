<p><strong>Công việc mới đã được khởi tạo!</strong></p>

<p><strong>Tên công việc:</strong> {{ $work->work_name }}</p>
<p><strong>Thuộc dự án:</strong> {{ $work->project->project_name ?? 'Không xác định' }}</p>
<p><strong>Ngày bắt đầu:</strong> {{ $work->start_date?->format('d/m/Y') ?? 'Không xác định' }}</p>
<p><strong>Ngày kết thúc:</strong> {{ $work->end_date?->format('d/m/Y') ?? 'Không xác định' }}</p>

<p><strong>Người phụ trách:</strong> {{ $assigneesNames ?: 'Chưa phân công' }}</p>
