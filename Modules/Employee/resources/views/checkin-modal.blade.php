@if (!$exists)
    <div class="modal fade show" style="display:block; background:rgba(0,0,0,0.5)">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Check-in</h5>
                </div>
                <div class="modal-body">
                    <p>Bạn chưa check-in hôm nay. Hãy check-in để bắt đầu làm việc.</p>
                </div>
                <div class="modal-footer">
                    <form method="POST" action="{{ route('checkin') }}">
                        @csrf
                        <button class="btn btn-success">Check-in</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endif
