<!--begin::Sidebar-->
      <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
        <!--begin::Sidebar Brand-->
        <div class="sidebar-brand">
          <!--begin::Brand Link-->
          <a href="{{ url('/') }}" class="brand-link">
            <!--begin::Brand Image-->
            <img
              src="{{ asset('adminlte/dist/assets/img/AdminLTELogo.png') }}"
              alt="AdminLTE Logo"
              class="brand-image opacity-75 shadow"
            />
            <!--end::Brand Image-->
            <!--begin::Brand Text-->
            <span class="brand-text fw-light">SAKINAH</span>
            <!--end::Brand Text-->
          </a>
          <!--end::Brand Link-->
        </div>
        <!--end::Sidebar Brand-->
        <!--begin::Sidebar Wrapper-->
        <div class="sidebar-wrapper">
          <nav class="mt-2">
            <!--begin::Sidebar Menu-->
             <ul
              class="nav sidebar-menu flex-column"
              data-lte-toggle="treeview"
              role="menu"
              data-accordion="false"
            >

              <li class="nav-header">MAIN NAVIGATION</li>
              <li class="nav-item">
                <a href="{{ route('backend.dashboard') }}" class="nav-link {{ request()->is('backend') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-speedometer2"></i>
                  <p>Dashboard</p>
                </a>
              </li>

              <li class="nav-header">MASTER DATA</li>
              <li class="nav-item">
                <a href="{{ route('jabatan.index') }}" class="nav-link {{ request()->is('backend/jabatan*') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-briefcase"></i>
                  <p>Jabatan</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('eselon.index') }}" class="nav-link {{ request()->is('backend/eselon*') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-bar-chart"></i>
                  <p>Eselon</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('jenis-jabatan.index') }}" class="nav-link {{ request()->is('backend/jenis-jabatan*') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-tags"></i>
                  <p>Jenis Jabatan</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('golongan.index') }}" class="nav-link {{ request()->is('backend/golongan*') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-award"></i>
                  <p>Golongan</p>
                </a>
              </li>

              <li class="nav-header">PROGRAM & KEGIATAN</li>
              <li class="nav-item">
                <a href="{{ route('program.index') }}" class="nav-link {{ request()->is('backend/program*') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-journal-code"></i>
                  <p>Program</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('kegiatan.index') }}" class="nav-link {{ request()->is('backend/kegiatan*') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-kanban"></i>
                  <p>Kegiatan</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('sub-kegiatan.index') }}" class="nav-link {{ request()->is('backend/sub-kegiatan*') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-list-task"></i>
                  <p>Sub Kegiatan</p>
                </a>
              </li>

              <li class="nav-header">ORGANISASI</li>
              <li class="nav-item">
                <a href="{{ route('opd.index') }}" class="nav-link {{ request()->is('backend/opd*') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-building"></i>
                  <p>OPD</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('sub-opd.index') }}" class="nav-link {{ request()->is('backend/sub-opd*') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-diagram-2"></i>
                  <p>Sub OPD</p>
                </a>
              </li>

              <li class="nav-header">LAINNYA</li>
              <li class="nav-item">
                <a href="{{ route('satuan.index') }}" class="nav-link {{ request()->is('backend/satuan*') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-rulers"></i>
                  <p>Satuan</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('pegawai.index') }}" class="nav-link {{ request()->is('backend/pegawai*') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-person-badge"></i>
                  <p>Pegawai</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('backend.user.index') }}" class="nav-link {{ request()->is('backend/user*') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-people"></i>
                  <p>User</p>
                </a>
              </li>

            </ul>
            <!--end::Sidebar Menu-->
          </nav>
        </div>
        <!--end::Sidebar Wrapper-->
      </aside>
