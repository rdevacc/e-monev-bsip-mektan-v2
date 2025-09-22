  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

      <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-heading">Utama</li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{route('dashboard')}}">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('activity') ? 'active' : '' }}" href="{{route('activity.index')}}">
                <i class="bi bi-grid"></i>
                <span>Kegiatan</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('export') ? 'active' : '' }}" href="{{route('export.index')}}">
                <i class="bi bi-grid"></i>
                <span>Cetak Laporan</span>
            </a>
        </li>

        @canany(['isSuperAdmin', 'isAdmin'])
        <li class="nav-heading">Super Admin</li>
        
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('work-group') ? 'active' : '' }}" href="{{route('work-group.index')}}">
                <i class="bi bi-grid"></i>
                <span>Kelompok Kerja</span>
            </a>
        </li>
        
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('work-team') ? 'active' : '' }}" href="{{route('work-team.index')}}">
                <i class="bi bi-grid"></i>
                <span>Tim Kerja</span>
            </a>
        </li>  

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('user') ? 'active' : '' }}" href="{{route('user.index')}}">
                <i class="bi bi-grid"></i>
                <span>Users</span>
            </a>
        </li>
        @endcanany
        
        @can('isSuperAdmin')
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('role') ? 'active' : '' }}" href="{{route('role.index')}}">
                <i class="bi bi-shield-check"></i>
                <span>Roles</span>
            </a>
        </li>
        @endcan
      </ul>
  </aside>
