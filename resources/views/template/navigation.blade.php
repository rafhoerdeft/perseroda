<ul class="metismenu" id="menu">

    @php
        $navigation = session('nav');
    @endphp

    @foreach ($navigation as $nav)
        @if (in_array(Auth::user()->role->nama_role, $nav['role']))
            @if (isset($nav['header']))
                <li class="menu-label">{{ $nav['header'] }}</li>
            @else
                <li>
                    <a href="{{ $nav['url'] }}" class="{{ $nav['child'] != null ? 'has-arrow' : '' }}">
                        <div class="parent-icon">
                            <i class="{{ $nav['icon'] }}"></i>
                        </div>
                        <div class="menu-title">{{ $nav['title'] }}</div>
                    </a>
                    @if ($nav['child'] != null)
                        <ul>
                            @foreach ($nav['child'] as $child)
                                <li>
                                    <a href="{{ $child['url'] }}"
                                        class="{{ $child['child'] != null ? 'has-arrow' : '' }}">
                                        <i class="bx bx-right-arrow-alt"></i> {{ $child['title'] }}
                                    </a>
                                    @if ($child['child'] != null)
                                        <ul>
                                            @foreach ($child['child'] as $item)
                                                <li>
                                                    <a class="" href="{{ $item['url'] }}">
                                                        <i class="bx bx-right-arrow-alt"></i> {{ $item['title'] }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endif
        @endif
    @endforeach

    {{-- <li class="menu-label">UI Elements</li> --}}

    {{-- <li>
        <a href="widgets.html">
            <div class="parent-icon"><i class='bx bx-briefcase-alt-2'></i>
            </div>
            <div class="menu-title">Widgets</div>
        </a>
    </li> --}}

    {{-- <li>
        <a class="has-arrow" href="javascript:;">
            <div class="parent-icon"><i class="bx bx-menu"></i>
            </div>
            <div class="menu-title">Menu Levels</div>
        </a>
        <ul>
            <li> <a class="has-arrow" href="javascript:;"><i class="bx bx-right-arrow-alt"></i>Level
                    One</a>
                <ul>
                    <li> <a class="has-arrow" href="javascript:;"><i class="bx bx-right-arrow-alt"></i>Level
                            Two</a>
                        <ul>
                            <li> <a href="javascript:;"><i class="bx bx-right-arrow-alt"></i>Level
                                    Three</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
        </ul>
    </li> --}}
</ul>
