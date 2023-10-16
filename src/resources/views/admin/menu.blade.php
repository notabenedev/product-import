@php
    $active = (strstr($currentRoute, "admin.ymls.") !== false) ||
              (strstr($currentRoute, "admin.ymls.") !== false);
@endphp

@if ($theme == "sb-admin")
    <li class="nav-item {{ $active ? " active" : "" }}">
        @can("viewAny", \App\ImportYml::class)
            <a href="{{ route("admin.ymls.index") }}"
               class="nav-link{{ strstr($currentRoute, ".ymls.") !== false ? " active" : "" }}">
                <i class="fas fa-file-import"></i>
                <span>Импорт Каталога</span>
            </a>
        @endcan
    </li>
@else
    <li class="nav-item dropdown">
        @can("viewAny", \App\ImportYml::class)
            <a href="{{ route("admin.ymls.index") }}"
               class="nav-link">
                Импорт Каталога
            </a>
        @endcan
    </li>
@endif
