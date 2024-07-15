<nav id="navMain" class="navbar navbar-expand-md shadow-sm">

    <button class="navbar-toggler mx-2 p-3 border-0"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navMainContent"
            aria-controls="navMainContent"
            aria-expanded="false">
        <i class="fa fa-bars"></i>
    </button>

    <div id="navMainContent" class="collapse navbar-collapse h-100">

        <button class="navbar-close btn border-0 hide-for-medium-up mx-1 p-4"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navMainContent"
                aria-controls="navMainContent">
            <i class="fas fa-times"></i>
        </button>

        <ul class="navbar-nav mx-auto">
            @foreach($items as $item)
                <li class="nav-item">
                    <a href="{{ route($item->route) }}"
                       class="nav-link {{ $item->class ?? '' }} {{ str_contains(request()->route()->getName(), $item->route) ? 'active' : '' }}">
                            {!! $item->before ?? '' !!}{{ $item->name }}{!! $item->after ?? '' !!}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</nav>