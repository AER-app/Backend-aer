<aside id="sidebar-wrapper">
    <div class="sidebar-brand">
        <a href="index.html">Aer</a>
    </div>
    <div class="sidebar-brand sidebar-brand-sm">
        <a href="index.html">Aer</a>
    </div>
    <ul class="sidebar-menu">
        <li class="menu-header">Dashboard</li>
        <li class="nav-item dropdown {{(request()->is('dashboard')) ? 'active' : ''}}">
            <a href="#" class="nav-link has-dropdown"><i class="fas fa-fire"></i><span>Dashboard</span></a>
            <ul class="dropdown-menu">
                <li class="{{(request()->is('dashboard')) ? 'active' : ''}}"><a class="nav-link" href="{{ route('admin.dashboard') }}">General Dashboard</a></li>
                <li><a class="nav-link" href="index.html">Ecommerce Dashboard</a></li>
            </ul>
        </li>
        <li class="menu-header">Starter</li>
        <li class="nav-item dropdown {{(request()->is('driver')) ? 'active' : ''}}">
            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-user"></i>
                <span>Driver</span></a>
            <ul class="dropdown-menu">
                <li class="{{(request()->is('driver')) ? 'active' : ''}}"><a class="nav-link" href="{{route('driver')}}">Data Driver</a></li>
                <li><a class="nav-link" href="#">Transparent Sidebar</a></li>
                <li><a class="nav-link" href="#">Top Navigation</a></li>
            </ul>
        </li>
        <li class="nav-item dropdown {{(request()->is('lapak')) ? 'active' : ''}}">
            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-home"></i>
                <span>Lapak</span></a>
            <ul class="dropdown-menu">
                <li class="{{(request()->is('lapak')) ? 'active' : ''}}"><a class="nav-link" href="{{route('lapak')}}">Data Lapak</a></li>
                <li><a class="nav-link" href="#">Transparent Sidebar</a></li>
                <li><a class="nav-link" href="#">Top Navigation</a></li>
            </ul>
        </li>
        <li class="nav-item dropdown {{(request()->is('customer')) ? 'active' : ''}}">
            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-user"></i>
                <span>Customer</span></a>
            <ul class="dropdown-menu">
                <li class="{{(request()->is('customer')) ? 'active' : ''}}"><a class="nav-link" href="{{route('customer')}}">Data Customer</a></li>
                <li><a class="nav-link" href="#">Transparent Sidebar</a></li>
                <li><a class="nav-link" href="#">Top Navigation</a></li>
            </ul>
        </li>
        <li><a class="nav-link" href="#"><i class="far fa-square"></i> <span>Blank Page</span></a></li>

        <div class="mt-4 mb-4 p-3 hide-sidebar-mini">
            <a href="https://getstisla.com/docs" class="btn btn-primary btn-lg btn-block btn-icon-split">
                <i class="fas fa-rocket"></i> Documentation
            </a>
        </div>
</aside>