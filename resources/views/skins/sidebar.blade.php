<aside class="main-sidebar bg-dark sidebar-dark-olive">
    <a href="{{ url('/') }}" class="brand-link text-center py-2">
        <span class="brand-text font-weight-light" style="font-size: 30px">Green<b>-to-Green</b></span>
    </a>

    <div class="sidebar">
        <div class="user-panel mt-2 pb-3 mb-2 d-flex flex-column align-items-center">
            <div class="img-circle-medium img-contain bg-white mb-3" style="background-image: url('{{ asset('dist/img/noimage.png') }}')"></div>
            <div class="user-info">
                <a href="#" class="info-name d-block"></a>
                <a href="#" class="info-level d-block"></a>
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item{{ isParentMenuOpen(DBRoutes::achievement, $route) }}">
                    <a href="{{ route(DBRoutes::achievement) }}" class="nav-link{{ isMenuActive($route, DBRoutes::achievement) }}">
                        <i class="nav-icon fas fa-tasks"></i>
                        <p>Achievement</p>
                    </a>
                </li>
                <li class="nav-item{{ isParentMenuOpen(DBRoutes::user, $route) }}">
                    <a href="{{ route(DBRoutes::user) }}" class="nav-link{{ isMenuActive($route, DBRoutes::user) }}">
                        <i class="nav-icon fas fa-user"></i>
                        <p>Users</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
