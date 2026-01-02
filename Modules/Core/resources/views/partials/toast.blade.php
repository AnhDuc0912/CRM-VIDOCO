@push('scripts')
    <script>
        @if (session('success'))
            Lobibox.notify('success', {
                title: 'Thành công',
                msg: '{{ session('success') }}',
                position: 'top right',
                delay: 5000,
                sound: false,
                width: 350,
                showClass: 'fadeInDown',
                hideClass: 'fadeOutUp',
                icon: 'bx bx-check-circle',
                rounded: true,
                class: 'my-lobibox-toast'
            });
        @endif
        @if (session('error') || $errors->any())
            Lobibox.notify('error', {
                title: 'Lỗi',
                msg: '{{ $errors->any() ? $errors->first() : session('error') }}',
                position: 'top right',
                delay: 5000,
                sound: false,
                width: 350,
                showClass: 'fadeInDown',
                hideClass: 'fadeOutUp',
                icon: 'bx bx-error',
                rounded: true,
                class: 'my-lobibox-toast'
            });
        @endif
    </script>
@endpush
