  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

      <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-heading">Utama</li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{route('dashboard')}}">
                <iconify-icon icon="material-symbols:dashboard-outline-rounded" style="font-size: 18px; margin-right: 8px;"></iconify-icon>
                <span>Dashboard</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('activity') ? 'active' : '' }}" href="{{route('activity.index')}}">
                <iconify-icon icon="carbon:user-activity" style="font-size: 18px; margin-right: 8px;"></iconify-icon>
                <span>Kegiatan</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('export') ? 'active' : '' }}" href="{{route('export.index')}}">
                <iconify-icon icon="lsicon:report-outline" style="font-size: 18px; margin-right: 8px;"></iconify-icon>
                <span>Cetak Laporan</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" 
                data-target="#guide-nav" 
                href="javascript:void(0);">
                <iconify-icon icon="tdesign:book-unknown" style="font-size: 18px; margin-right: 8px;"></iconify-icon>
                <span>Petunjuk Penggunaan</span>
                <i class="bi bi-chevron-down chevron-dropdown" style="margin-left: auto;"></i>
            </a>
            <ul id="guide-nav" 
                class="nav-content {{ request()->routeIs('guide.*') ? 'show' : '' }}">
                <li>
                    <a href="{{ route('guide.pj') }}" 
                        class="{{ request()->routeIs('guide.pj') ? 'active' : '' }}">
                        <i class="bi bi-circle"></i><span>Panduan PJ</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('guide.admin') }}" 
                        class="{{ request()->routeIs('guide.admin') ? 'active' : '' }}">
                        <i class="bi bi-circle"></i><span>Panduan Admin</span>
                    </a>
                </li>
            </ul>
        </li>

        @canany(['isSuperAdmin', 'isAdmin'])
        <li class="nav-heading">Super Admin</li>
        
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('work-group') ? 'active' : '' }}" href="{{route('work-group.index')}}">
                <iconify-icon icon="material-symbols:activity-zone-outline" style="font-size: 18px; margin-right: 8px;"></iconify-icon>
                <span>Kelompok Kerja</span>
            </a>
        </li>
        
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('work-team') ? 'active' : '' }}" href="{{route('work-team.index')}}">
                <iconify-icon icon="mdi-light:group" style="font-size: 18px; margin-right: 8px;"></iconify-icon>
                <span>Tim Kerja</span>
            </a>
        </li>  

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('user') ? 'active' : '' }}" href="{{route('user.index')}}">
                <iconify-icon icon="oui:users" style="font-size: 18px; margin-right: 8px;"></iconify-icon>
                <span>Users</span>
            </a>
        </li>
        @endcanany
        
        @can('isSuperAdmin')
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('role') ? 'active' : '' }}" href="{{route('role.index')}}">
                <iconify-icon icon="eos-icons:role-binding-outlined" style="font-size: 18px; margin-right: 8px;"></iconify-icon>
                <span>Roles</span>
            </a>
        </li>
        @endcan
      </ul>
  </aside>
