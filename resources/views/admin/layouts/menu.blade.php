<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <h5 style="margin: 0 !important;">
            <a href="{{ route('dashboard') }}" class="app-brand-link">
                Pc maker
            </a>
        </h5>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="bx menu-toggle-icon d-none d-xl-block fs-4 align-middle"></i>
            <i class="bx bx-x d-block d-xl-none bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-divider mt-0"></div>

    <ul class="menu-inner py-1 ps ps--active-y">
        <li class="menu-item {{ Request::is('/') || Request::is('dashboard*') ? 'active' : '' }}">
            <a href="{{route('dashboard')}}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="Панели управления">Панели управления</div>
            </a>
        </li>
        <li class="menu-item {{ Request::is('products*') ? 'active' : '' }}">
            <a href="{{ route('products.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-box"></i> <!-- Измените здесь на нужную вам иконку -->
                <div data-i18n="Панели управления">Продукты</div>
            </a>
        </li>
    </ul>
</aside>

<div class="layout-overlay layout-menu-toggle"></div>
