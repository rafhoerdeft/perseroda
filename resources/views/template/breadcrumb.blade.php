<div class="page-breadcrumb d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">{{ end($breadcrumb) }}</div>
    <div class="ps-3 d-none d-md-block">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item">
                    <a href="{{ url('dashboard') }}"><i class="bx bx-home-alt"></i></a>
                </li>
                @foreach ($breadcrumb as $key => $item)
                    <li class="breadcrumb-item {{ $key === array_key_last($breadcrumb) ? 'active' : '' }}">
                        @if ($key === array_key_last($breadcrumb))
                            {{ $item }}
                        @else
                            <a href="{{ url($key) }}">{{ $item }}</a>
                        @endif
                    </li>
                @endforeach
            </ol>
        </nav>
    </div>

    <div class="ms-auto">
        @yield('button-top')
    </div>

    {{-- <div class="ms-auto">
        <div class="btn-group">
            <button type="button" class="btn btn-primary">Settings</button>
            <button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split"
                data-bs-toggle="dropdown"> <span class="visually-hidden">Toggle Dropdown</span>
            </button>
            <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end"> <a class="dropdown-item"
                    href="javascript:;">Action</a>
                <a class="dropdown-item" href="javascript:;">Another action</a>
                <a class="dropdown-item" href="javascript:;">Something else here</a>
                <div class="dropdown-divider"></div> <a class="dropdown-item" href="javascript:;">Separated
                    link</a>
            </div>
        </div>
    </div> --}}
</div>
