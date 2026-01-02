<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                @php
                    if (isset($breadcrumbs) && is_array($breadcrumbs)) {
                        $breadcrumbs = $breadcrumbs;
                    } else {
                        $breadcrumbs = null;
                    }
                @endphp

                @if ($breadcrumbs && count($breadcrumbs) > 0)
                    @foreach ($breadcrumbs as $index => $breadcrumb)
                        @if ($index === count($breadcrumbs) - 1)
                            <li class="breadcrumb-item active" aria-current="page">
                                {{ $breadcrumb['title'] }}
                            </li>
                        @else
                            <li class="breadcrumb-item">
                                @if (isset($breadcrumb['url']) && $breadcrumb['url'])
                                    <a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['title'] }}</a>
                                @else
                                    {{ $breadcrumb['title'] }}
                                @endif
                            </li>
                        @endif
                    @endforeach
                @endif
            </ol>
        </nav>
    </div>
</div>
