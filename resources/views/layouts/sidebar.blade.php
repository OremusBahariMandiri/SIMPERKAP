{{-- resources/views/layouts/sidebar.blade.php --}}
<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <a href="{{ route('home') }}" class="sidebar-brand">
            <i class="fas fa-ship"></i>
            <span class="brand-text">SIMPERKAP</span>
        </a>
        <button type="button" class="sidebar-close d-md-none" id="sidebarClose">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <div class="sidebar-content">
        <div class="sidebar-menu">
            <div class="sidebar-heading">Menu Utama</div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('home') ? 'active' : '' }}" href="{{ route('home') }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span class="nav-text">Dashboard</span>
                    </a>
                </li>

                {{-- Data Master Dropdown --}}
                <li class="nav-item">
                    <a class="nav-link sidebar-menu-item {{
                        request()->is('users*') ||
                        request()->is('perusahaan*') ||
                        request()->is('jenis-kapal*') ||
                        request()->is('kapal*') ||
                        request()->is('kategori-dokumen*') ||
                        request()->is('nama-dokumen*') ? 'active open' : ''
                    }}" href="javascript:void(0);" data-menu="dataMaster">
                        <div class="d-flex align-items-center justify-content-between w-100">
                            <div class="menu-icon-text">
                                <i class="fas fa-database"></i>
                                <span class="nav-text">Data Master</span>
                            </div>
                            <i class="fas fa-chevron-down submenu-indicator"></i>
                        </div>
                    </a>
                    <ul class="sidebar-submenu {{
                        request()->is('users*') ||
                        request()->is('perusahaan*') ||
                        request()->is('jenis-kapal*') ||
                        request()->is('kapal*') ||
                        request()->is('kategori-dokumen*') ||
                        request()->is('nama-dokumen*') ? 'show' : ''
                    }}" id="dataMaster">
                        {{-- Users --}}
                        @if(Auth::user()->is_admin || Auth::user()->hasAccess('pengguna'))
                        <li class="nav-item">
                            <a class="submenu-link {{ request()->is('users*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                                <i class="fas fa-users"></i>
                                <span>Pengguna</span>
                            </a>
                        </li>
                        @endif

                        {{-- Perusahaan --}}
                        @if(Auth::user()->is_admin || Auth::user()->hasAccess('perusahaan'))
                        <li class="nav-item">
                            <a class="submenu-link {{ request()->is('perusahaan*') ? 'active' : '' }}" href="{{ route('perusahaan.index') }}">
                                <i class="fas fa-building"></i>
                                <span>Perusahaan</span>
                            </a>
                        </li>
                        @endif

                        {{-- Jenis Kapal --}}
                        @if(Auth::user()->is_admin || Auth::user()->hasAccess('jenis_kapal'))
                        <li class="nav-item">
                            <a class="submenu-link {{ request()->is('jenis-kapal*') ? 'active' : '' }}" href="{{ route('jenis-kapal.index') }}">
                                <i class="fas fa-ship"></i>
                                <span>Jenis Kapal</span>
                            </a>
                        </li>
                        @endif

                        {{-- Kapal --}}
                        @if(Auth::user()->is_admin || Auth::user()->hasAccess('kapal'))
                        <li class="nav-item">
                            <a class="submenu-link {{ request()->is('kapal*') ? 'active' : '' }}" href="{{ route('kapal.index') }}">
                                <i class="fas fa-anchor"></i>
                                <span>Kapal</span>
                            </a>
                        </li>
                        @endif

                        {{-- Kategori Dokumen --}}
                        @if(Auth::user()->is_admin || Auth::user()->hasAccess('kategori_dokumen'))
                        <li class="nav-item">
                            <a class="submenu-link {{ request()->is('kategori-dokumen*') ? 'active' : '' }}" href="{{ route('kategori-dokumen.index') }}">
                                <i class="fas fa-folder"></i>
                                <span>Kategori Dokumen</span>
                            </a>
                        </li>
                        @endif

                        {{-- Nama Dokumen --}}
                        @if(Auth::user()->is_admin || Auth::user()->hasAccess('nama_dokumen'))
                        <li class="nav-item">
                            <a class="submenu-link {{ request()->is('nama-dokumen*') ? 'active' : '' }}" href="{{ route('nama-dokumen.index') }}">
                                <i class="fas fa-file-alt"></i>
                                <span>Nama Dokumen</span>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>

                {{-- Dokumen Kapal (di luar dropdown) --}}
                @if(Auth::user()->is_admin || Auth::user()->hasAccess('dokumen_kapal'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('dokumen-kapal*') && !request()->is('dokumen-kapal/monitoring*') ? 'active' : '' }}" href="{{ route('dokumen-kapal.index') }}">
                        <i class="fas fa-file-contract"></i>
                        <span class="nav-text">Dokumen Kapal</span>
                    </a>
                </li>
                @endif

                @if(Auth::user()->is_admin || Auth::user()->hasAccess('dokumen_kapal'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('ship-particular*') && !request()->is('ship-particular/monitoring*') ? 'active' : '' }}" href="{{ route('ship-particular.index') }}">
                        <i class="fas fa-file-invoice"></i>
                        <span class="nav-text">Ship Particular</span>
                    </a>
                </li>
                @endif
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('settings.index') && !request()->is('settings.index') ? 'active' : '' }}" href="{{ route('settings.index') }}">
                        <i class="fas fa-gear"></i>
                        <span class="nav-text">Pengaturan</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>