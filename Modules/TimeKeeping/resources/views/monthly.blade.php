@extends('core::layouts.app')

@section('title', 'Chốt chấm công tháng')

@section('content')

    <div class="page-content">

        <div class="card">
            <div class="card-body">

                <form method="GET" class="d-flex justify-content-end mb-3" style="max-width: 300px;">
                    <input type="month" name="month" class="form-control" value="{{ $month }}">
                    <button class="btn btn-primary ms-2">Lọc</button>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped text-center align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>STT</th>
                                <th>Nhân viên</th>

                                @foreach ($workingDays as $d)
                                    <th>{{ $d }}</th>
                                @endforeach

                                <th>Nghỉ</th>
                                <th>Đi trễ</th>
                                <th>Về sớm</th>
                                <th>Có làm</th>
                            </tr>
                        </thead>


                        <tbody>
                            @foreach ($summary as $i => $item)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $item['employee']->full_name }}</td>

                                    @foreach ($item['days'] as $day => $status)
                                        <td>
                                            @if ($status)
                                                @if ($status['check_in'] && $status['check_out'])
                                                    <span class="badge bg-success">✓</span>
                                                @else
                                                    <span class="badge bg-warning text-dark">Chưa Checkout</span>
                                                @endif
                                            @else
                                                <span class="text-danger">X</span>
                                            @endif
                                        </td>
                                    @endforeach

                                    <td>{{ $item['day_off'] }}</td>
                                    <td class="text-danger">{{ $item['late_days'] }}</td>
                                    <td class="text-danger">{{ $item['early_days'] }}</td>
                                    <td class="text-success">{{ $item['work_days'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>


                    </table>
                </div>

            </div>
        </div>
    </div>

@endsection
