  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

      <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-heading">Utama</li>

          <li class="nav-item">
              <a class="nav-link collapsed" href="{{route('dashboard')}}">
                  <i class="bi bi-grid"></i>
                  <span>Dashboard</span>
              </a>
          </li><!-- End Dashboard Nav -->

          <li class="nav-item">
              <a class="nav-link collapsed" href="{{route('kegiatan-index')}}">
                  <i class="bi bi-menu-button-wide"></i><span>Kegiatan</span></i>
              </a>
          </li><!-- End Kegiatan Page Nav -->

          <li class="nav-item">
              <a class="nav-link collapsed" href="#">
                  <i class="bi bi-archive"></i><span>Laporan</span></i>
              </a>
          </li><!-- End Report Page Nav -->


          <li class="nav-heading">Super Admin</li>

          <li class="nav-item">
              <a class="nav-link collapsed" href="{{route('user-index')}}">
                  <i class="bi bi-people"></i>
                  <span>Users</span>
              </a>
          </li><!-- End Users -->
         
          <li class="nav-item">
              <a class="nav-link collapsed" href="{{route('kelompok-index')}}">
                  <i class="bi bi-diagram-2"></i>
                  <span>Kelompok</span>
              </a>
          </li><!-- End Kelompok -->
         
          <li class="nav-item">
              <a class="nav-link collapsed" href="{{route('subkelompok-index')}}">
                  <i class="bi bi-diagram-3"></i>
                  <span>Subkelompok</span>
              </a>
          </li><!-- End Subkelompok -->

          <li class="nav-item">
              <a class="nav-link collapsed" href="{{route('role-index')}}">
                  <i class="bi bi-shield-check"></i>
                  <span>Roles</span>
              </a>
          </li><!-- End Roles -->
      </ul>
  </aside><!-- End Sidebar-->
