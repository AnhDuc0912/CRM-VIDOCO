@extends('core::layouts.app')
@use('App\Helpers\FileHelper')
@use('Modules\Employee\Enums\EmployeeFileTypeEnum')

@section('title', 'Danh sách chấm công')

@section('content')
    <div class="page-content">

        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Chấm Công</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="#"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Danh Sách Chấm Công</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form method="GET" class="d-flex justify-content-end align-items-end mb-3 gap-2">

                    <div class="d-flex flex-column" style="width: 220px;">
                        <input type="date" name="date" class="form-control" value="">
                    </div>

                    <button class="btn btn-primary" style="height: 40px;">
                        Lọc
                    </button>

                </form>




                <div class="table-responsive">
                    <table id="example2" class="table table-striped table-bordered w-100">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nhân viên</th>
                                <th>Check-in</th>
                                <th>IP / Thiết bị</th>
                                <th>Check-out</th>
                                <th>IP / Thiết bị</th>
                            </tr>
                        </thead>


                        <tbody>
                            @foreach ($timekeepings as $tk)
                                <tr>
                                    <td>{{ $tk->id }}</td>

                                    <td>
                                        {{ $tk->employee?->full_name ?? 'N/A' }}
                                    </td>

                                    <td>
                                        @if ($tk->check_in)
                                            <span class="badge bg-success">
                                                {{ \Carbon\Carbon::parse($tk->check_in)->format('H:i - d/m/Y') }}
                                            </span>
                                            @if ($tk->late)
                                                <br>
                                                <small class="text-danger">Trễ: {{ $tk->late }}</small>
                                            @endif
                                        @else
                                            Chưa checkin
                                        @endif
                                    </td>


                                    <td>
                                        <small>
                                            <strong>IP:</strong> {{ $tk->ip_check_in ?? 'N/A' }} <br>
                                            <strong>Device:</strong> {{ $tk->device_check_in ?? 'N/A' }}
                                        </small>
                                    </td>

                                    <td>
                                        @if ($tk->check_out)
                                            <span class="badge bg-primary">
                                                {{ \Carbon\Carbon::parse($tk->check_out)->format('H:i - d/m/Y') }}
                                            </span>
                                            @if ($tk->early_leave)
                                                <br>
                                                <small class="text-danger">Về sớm: {{ $tk->early_leave }}</small>
                                            @endif
                                        @else
                                            <span class="badge bg-warning text-dark">Chưa checkout</span>
                                        @endif
                                    </td>


                                    <td>
                                        <small>
                                            <strong>IP:</strong> {{ $tk->ip_check_out ?? 'N/A' }} <br>
                                            <strong>Device:</strong> {{ $tk->device_check_out ?? 'N/A' }}
                                        </small>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>

            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#example2').DataTable({
                autoWidth: false,
                scrollX: true,
                lengthChange: false,
                buttons: ['excel', 'pdf', 'colvis'],
                order: [[0, 'desc']] 
            });

        });
    </script>
@endpush
