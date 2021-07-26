<aside id="sidebar-wrapper">
    <div class="sidebar-brand">
        <a href="index.html">Aer</a>
    </div>
    <div class="sidebar-brand sidebar-brand-sm">
        <a href="index.html">Aer</a>
    </div>
    <ul class="sidebar-menu">
        @if(auth()->user()->role == 'admin')
            <li class="menu-header">Dashboard</li>
            <li class="nav-item dropdown {{(request()->is('dashboard')) ? 'active' : ''}}">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-fire"></i><span>Dashboard</span></a>
                <ul class="dropdown-menu">
                    <li class="{{(request()->is('dashboard')) ? 'active' : ''}}"><a class="nav-link" href="{{ route('admin.dashboard') }}">General Dashboard</a></li>
                </ul>
            </li>
            <li class="menu-header">____</li>
            <li class="nav-item dropdown {{(request()->is('driver')) ? 'active' : ''}}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-id-badge"></i>
                    <span>Driver</span></a>
                <ul class="dropdown-menu">
                    <li class="{{(request()->is('driver')) ? 'active' : ''}}"><a class="nav-link" href="{{route('driver')}}">Data Driver</a></li>
                    <li class="{{(request()->is('driver/posting')) ? 'active' : ''}}"><a class="nav-link" href="{{ route('driver-posting')}}">Posting</a></li>
                    <!--<li class="{{(request()->is('driver/posting')) ? 'active' : ''}}"><a class="nav-link" href="#">Posting</a></li>-->
                </ul>
            </li>
            <li class="nav-item dropdown {{(request()->is('lapak')) ? 'active' : ''}}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-store"></i>
                    <span>Lapak</span></a>
                <ul class="dropdown-menu">
                    <li class="{{(request()->is('lapak')) ? 'active' : ''}}"><a class="nav-link" href="{{route('lapak')}}">Data Lapak</a></li>
                    <li class="{{(request()->is('kategori_menu')) ? 'active' : ''}}"><a class="nav-link" href="{{ route('kategori_menu')}}">Kategori Menu</a></li>
                    <li class="{{(request()->is('lapak/menu')) ? 'active' : ''}}"><a class="nav-link" href="{{ route('lapak-menu')}}">Menu</a></li>
                    <li><a class="nav-link" href="#">Posting</a></li>
                </ul>
            </li>
            <li class="nav-item dropdown {{(request()->is('customer')) ? 'active' : ''}}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="far fa-user"></i>
                    <span>Customer</span></a>
                <ul class="dropdown-menu">
                    <li class="{{(request()->is('customer')) ? 'active' : ''}}"><a class="nav-link" href="{{route('customer')}}">Data Customer</a></li>
                    <li><a class="nav-link" href="#">Transparent Sidebar</a></li>
                    <li><a class="nav-link" href="#">Top Navigation</a></li>
                </ul>
            </li>
            
            <li class="nav-item dropdown {{(request()->is('order')) ? 'active' : ''}}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-shopping-cart"></i>
                    <span>Order</span></a>
                <ul class="dropdown-menu">
                    <li class="{{(request()->is('order')) ? 'active' : ''}}"><a class="nav-link" href="{{route('order')}}">Order</a></li>
                    <li class="{{(request()->is('order-posting')) ? 'active' : ''}}"><a class="nav-link" href="{{route('order-posting')}}">Order Posting</a></li>
                </ul>
            </li>
            <li class="nav-item dropdown {{(request()->is('promosi')) ? 'active' : ''}}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-grid"></i>
                    <span>Lain - lain</span></a>
                <ul class="dropdown-menu">
                    <li class="{{(request()->is('promosi')) ? 'active' : ''}}"><a class="nav-link" href="{{ route('promosi') }}"><span>Halaman Promosi</span></a></li>
                    <li class="{{(request()->is('bantuan')) ? 'active' : ''}}"><a class="nav-link" href="{{ route('bantuan') }}"> <span>Halaman Bantuan</span></a></li>
                    <li class="{{(request()->is('privacy_policy')) ? 'active' : ''}}"><a class="nav-link" href="{{ route('privacy_policy.index') }}"> <span>Privacy Policy</span></a></li>
                    <li class="{{(request()->is('admintestimoni')) ? 'active' : ''}}"><a class="nav-link" href="{{ route('testimoni') }}"><span>Testimoni</span></a></li>
                    <li class="{{(request()->is('promo_ongkir')) ? 'active' : ''}}"><a class="nav-link" href="{{ route('promo_ongkir') }}"><span>Promo Ongkir</span></a></li>
                    <li class="{{(request()->is('broadcast_notif')) ? 'active' : ''}}"><a class="nav-link" href="{{ route('broadcast_notif') }}"><span>Broadcast Notif</span></a></li>
                </ul>
            </li>
        @endif
        
        @if(auth()->user()->role == 'admin_order_offline')
            <li class="menu-header">Dashboard</li>
            <li class="nav-item dropdown {{(request()->is('dashboard-admin_order_offline')) ? 'active' : ''}}">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-fire"></i><span>Dashboard</span></a>
                <ul class="dropdown-menu">
                    <li class="{{(request()->is('dashboard-admin_order_offline')) ? 'active' : ''}}"><a class="nav-link" href="{{ route('admin_order_offline.dashboard') }}">General Dashboard</a></li>
                </ul>
            </li>
            <li class="menu-header">____</li>
            <li class="{{(request()->is('kelola_order_offline')) ? 'active' : ''}}"><a class="nav-link" href="{{ route('kelola.order-offline') }}"><i class="fas fa-shopping-cart"></i> <span>Kelola Order Offline</span></a></li>
        @endif
        
        <div class="mt-4 mb-4 p-3 hide-sidebar-mini">
            <a href="#" class="btn btn-primary btn-lg btn-block btn-icon-split">
                <i class="fas fa-rocket"></i> Documentation
            </a>
        </div>
</aside>
