<div class="tab-pane fade" id="hosocongviec">
    <div class="card shadow-none border mb-0 radius-15">
        <div class="card-body">
            <h5 class="mb-3">Thông tin công việc hiện tại</h5>
            <div class="row mb-4">
                @include(
                    'employee::components.job-profile.info-current-job',
                    compact('employee'))
            </div>

            <hr>
            <h5 class="mt-4 mb-3">Thông tin lương & chế độ</h5>
            <p>Chính sách tiền lương là bảo mật. Không chia sẻ
                thông tin thu nhập cho những người không
                liên quan. </p>
            <hr>
            @include(
                'employee::components.job-profile.benefit',
                compact('employee'))
        </div>
    </div>
</div>
