@extends('core::layouts.app')
@use('Modules\Proposal\Enums\ProposalStatusEnum')
@use('Modules\Customer\Enums\CustomerTypeEnum')
@use('Modules\Core\Enums\PermissionEnum')

@section('title', 'Cập nhật báo giá')

@section('content')
    <div class="card shadow-none border mb-0 radius-15">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-3">Thông tin chung</h4>

                <a class="btn btn-info" href="{{ route('customers.create') }}"><i class="bx bx-plus me-1"></i>Thêm khách
                    hàng</a>
            </div>
            
            @if ($proposal->status == ProposalStatusEnum::REJECTED_REDO)
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>Lưu ý:</strong> Báo giá này đang ở trạng thái "Yêu cầu làm lại". 
                    Chỉ nhân viên được phân công (<strong>{{ $proposal->customer?->personInCharge?->full_name ?? 'Chưa có' }}</strong>) 
                    mới có thể upload file mới. File cũ sẽ được giữ lại để đối chiếu.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            <hr />
            <form id="proposal-form" method="POST" action="{{ route('proposals.update', $proposal->id) }}"
                enctype="multipart/form-data">
                @method('PUT')
                @csrf
                @include('proposal::components.form')

                <div class="row g-3 mb-4 text-center">
                    <div class="col-12">
                        <button class="btn btn-info" type="submit" id="submit-btn">Cập nhật</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('modules/proposal/js/validation/proposal-validation.js') }}"></script>
    <script src="{{ asset('modules/proposal/js/format-helper.js') }}"></script>
    <script>
        $(document).ready(function() {
            const proposalId = '{{ $proposal->id }}';

            if (proposalId) {
                $('#customer_select').val('{{ $proposal->customer_id }}').trigger('change');

                $.ajax({
                    url: '{{ route('customers.ajax.show', ['id' => ':id']) }}'
                        .replace(':id', '{{ $proposal->customer_id }}'),
                    type: 'GET',
                    beforeSend: function() {
                        $('#submit-btn').prop(
                            'disabled', true);
                    },
                    success: function(response) {
                        const type = response
                            .customer_type;
                        let customerName = '';

                        if (type == {{ CustomerTypeEnum::COMPANY }}) {
                            customerName = response.company_name ?? '';
                        } else {
                            customerName =
                                `${response.first_name ?? ''} ${response.last_name ?? ''}`;
                        }

                        $('#email').val(response.email ?? '');
                        $('#phone').val(response.phone ?? '');
                        $('#address').val(response.address ?? '');
                        $('#employee_id').val(response.person_in_charge?.full_name ?? '');
                        $('#customer_name').val(customerName ?? '');
                        $('#submit-btn').prop('disabled', false);
                    }
                });
            }
        });
    </script>
    <script>
        $(document).ready(function() {
            setTimeout(function() {
                $('#customer_select, #status_select')
                    .each(function() {
                        if ($(this).hasClass(
                                'select2-hidden-accessible')) {
                            $(this).select2('destroy');
                        }
                    });

                var selectConfig = {
                    theme: 'bootstrap4',
                    width: '100%',
                    placeholder: 'Chọn khách hàng',
                    language: {
                        noResults: function() {
                            return "Không tìm thấy kết quả";
                        }
                    }
                };

                try {
                    $('#customer_select').select2(selectConfig);
                } catch (e) {
                    console.error('✗ Customer select error:', e);
                }

                try {
                    $('#status_select').select2(selectConfig);
                } catch (e) {
                    console.error('✗ Status select error:', e);
                }
            }, 200);

            $('#customer_select').on('change', function() {
                var customerId = $(this).val();

                // Preserve validation state before AJAX call
                const wasValid = !$(this).hasClass('is-invalid');

                $.ajax({
                    url: '{{ route('customers.ajax.show', ['id' => ':id']) }}'
                        .replace(':id', customerId),
                    type: 'GET',
                    beforeSend: function() {
                        $('#submit-btn').prop(
                            'disabled', true);
                    },
                    success: function(response) {
                        const type = response
                            .customer_type;
                        let customerName = '';

                        if (type == {{ CustomerTypeEnum::COMPANY }}) {
                            customerName = response.company_name ?? '';
                        } else {
                            customerName =
                                `${response.first_name ?? ''} ${response.last_name ?? ''}`;
                        }

                        $('#email').val(response.email ?? '');
                        $('#phone').val(response.phone ?? '');
                        $('#address').val(response.address ?? '');
                        $('#employee_id').val(response.person_in_charge?.full_name ?? '');
                        $('#customer_name').val(customerName ?? '');
                        $('#submit-btn').prop('disabled', false);

                        // Maintain validation state after customer data is loaded
                        if (wasValid && customerId) {
                            $('#customer_select').removeClass('is-invalid');
                            $('#customer_select').next('.select2-container').removeClass(
                                'is-invalid');
                            $('#customer_select').next('.select2-container').find(
                                '.select2-selection').removeClass('is-invalid');
                        }
                    }
                });
            });
        });
    </script>
@endpush
