@use('App\Helpers\FileHelper')
<!--header-->
<header class="top-header">
    <nav class="navbar navbar-expand">
        <div class="left-topbar d-flex align-items-center">
            <a href="javascript:;" class="toggle-btn"> <i class="bx bx-menu"></i>
            </a>
        </div>
        <div class="flex-grow-1 search-bar">
            <div class="input-group">
                <button class="btn btn-search-back search-arrow-back" type="button"><i
                        class="bx bx-arrow-back"></i></button>
                <input type="text" class="form-control" placeholder="search" />
                <button class="btn btn-search" type="button"><i class="lni lni-search-alt"></i></button>
            </div>
        </div>
        <div class="right-topbar ms-auto">
            <ul class="navbar-nav">
                <li class="nav-item search-btn-mobile">
                    <a class="nav-link position-relative" href="javascript:;"> <i
                            class="bx bx-search vertical-align-middle"></i>
                    </a>
                </li>
                <li class="nav-item dropdown dropdown-lg">
                    <a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="javascript:;"
                        data-bs-toggle="dropdown"> <!--<span class="msg-count">6</span>-->
                        <i class="bx bx-comment-detail vertical-align-middle"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a href="javascript:;">
                            <div class="msg-header">
                                <h6 class="msg-header-title">6 New</h6>
                                <p class="msg-header-subtitle">Application Messages</p>
                            </div>
                        </a>
                        <div class="header-message-list">
                            @foreach ($messages ?? [] as $message)
                                <a class="dropdown-item" href="javascript:;">
                                    <div class="d-flex align-items-center">
                                        <div class="user-online">
                                            <img src="{{ asset('assets/images/avatars/' . $message['avatar']) }}"
                                                class="msg-avatar" alt="user avatar">
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="msg-name">{{ $message['name'] }} <span
                                                    class="msg-time float-end">{{ $message['time'] }}</span></h6>
                                            <p class="msg-info">{{ $message['message'] }}</p>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                        <a href="javascript:;">
                            <div class="text-center msg-footer">View All Messages</div>
                        </a>
                    </div>
                </li>
                <li class="nav-item dropdown dropdown-lg">
                    <a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="javascript:;"
                        data-bs-toggle="dropdown">

                        <i class="bx bx-bell vertical-align-middle"></i>

                        @if ($unreadCount > 0)
                            <span class="msg-count">{{ $unreadCount }}</span>
                        @endif
                    </a>

                    <div class="dropdown-menu dropdown-menu-end">
                        <a href="javascript:;">
                            <div class="msg-header">
                                <h6 class="msg-header-title">{{ $unreadCount }} Tin Mới</h6>
                                <p class="msg-header-subtitle">Thông Tin Nội Bộ</p>
                            </div>
                        </a>
                        <div class="header-notifications-list">
                            @foreach ($notifications as $notification)
                                <a class="dropdown-item" href="{{ $notification->url ?? '#' }}"
                                    onclick="markRead({{ $notification->id }})">

                                    <div class="d-flex align-items-center">
                                        <div class="notify bg-primary text-white">
                                            <i class="bx bx-file" style="width:45px;"></i>
                                        </div>

                                        <div class="flex-grow-1">
                                            <h6 class="msg-name">
                                                {{ $notification->title }}
                                                <span class="msg-time float-end">
                                                    {{ $notification->created_at->diffForHumans() }}
                                                </span>
                                            </h6>

                                            <p class="msg-info">
                                                {{ $notification->content }}
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            @endforeach

                        </div>
                        <a href="javascript:;">
                            <div class="text-center msg-footer"></div>
                        </a>
                    </div>
                </li>

                <li class="nav-item dropdown dropdown-user-profile">
                    <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="javascript:;"
                        data-bs-toggle="dropdown">
                        <div class="d-flex user-box align-items-center">
                            <div class="user-info">
                                <p class="user-name mb-0">{{ auth()->user()->name ?? 'Trần Thông' }}</p>
                                <p class="designattion mb-0">online</p>
                            </div>
                            <img src="{{ auth()->user()?->employee?->avatar ? FileHelper::getFileUrl(auth()->user()?->employee?->avatar) : asset('assets/images/avatars/avatar-1.png') }}"
                                class="user-img" alt="user avatar">
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a class="dropdown-item" href="#"><i class="bx bx-user"></i><span>Profile</span></a>
                        <a class="dropdown-item" href="#"><i class="bx bx-cog"></i><span>Settings</span></a>
                        <a class="dropdown-item" href="#"><i
                                class="bx bx-tachometer"></i><span>Dashboard</span></a>
                        <a class="dropdown-item" href="#"><i class="bx bx-wallet"></i><span>Earnings</span></a>
                        <a class="dropdown-item" href="#"><i
                                class="bx bx-cloud-download"></i><span>Downloads</span></a>

                        <div class="dropdown-divider"></div>

                        @php
                            $todayRecord = \Modules\TimeKeeping\Models\TimeKeeping::where(
                                'employee_id',
                                auth()->user()->employee->id,
                            )
                                ->whereDate('check_in', today())
                                ->first();

                            $checkedIn = $todayRecord ? true : false;
                            $checkedOut = $todayRecord && $todayRecord->check_out ? true : false;
                        @endphp
                        @if (!$checkedIn)
                            <form method="POST" action="{{ route('checkin') }}">
                                @csrf
                                <button class="dropdown-item text-success">
                                    <i class="bx bx-log-in"></i> Check-in
                                </button>
                            </form>
                        @elseif ($checkedIn && !$checkedOut)
                            <form method="POST" action="{{ route('checkout') }}">
                                @csrf
                                <button class="dropdown-item text-danger">
                                    <i class="bx bx-log-out"></i> Check-out
                                </button>
                            </form>
                        @else
                            <span class="dropdown-item text-muted"><i class="bx bx-check-circle"></i> Đã hoàn tất hôm
                                nay</span>
                        @endif

                        <div class="dropdown-divider mb-0"></div>

                        {{-- Logout --}}
                        <a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="bx bx-power-off"></i><span>Logout</span>
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>

                </li>
            </ul>
        </div>
    </nav>

</header>

<div class="modal fade" id="checkinModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Chấm công</h5>
            </div>

            <div class="modal-body">
                <div id="checkin-message"></div>
            </div>

            <div class="modal-footer" id="checkin-footer"></div>

        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        fetch("{{ route('checkin.status') }}")
            .then(res => res.json())
            .then(data => {
                let modal = new bootstrap.Modal(document.getElementById('checkinModal'));

                if (data.checked_in === false) {
                    document.getElementById("checkin-message").innerHTML =
                        "Chào mừng bạn đến với văn phòng Làm việc VIDOCO, vui lòng checkin!";

                    document.getElementById("checkin-footer").innerHTML = `
                    <form method="POST" action="{{ route('checkin') }}">
                        @csrf
                        <button class="btn btn-success">Check-in</button>
                    </form>
                `;

                    modal.show();
                } else {
                    document.getElementById("checkin-message").innerHTML =
                        "Bạn đã check-in lúc <b>" + data.time + "</b>";

                    document.getElementById("checkin-footer").innerHTML = `
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                `;
                }
            });
    });
</script>

<script>
    function markRead(id) {
        fetch("{{ url('document/notifications') }}/" + id + "/read", {
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
    }
</script>
