<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
      <a href="#" class="app-brand-link">
        <img src="{{asset('assets/myimg/logo.png')}}" class="app-brand-logo w-px-30 h-auto me-2 " alt="logo" />
            <span class="app-brand-text menu-text fw-bold">TOYOTA
              <br />
              <span class="fs-tiny fw-medium">CRM System</span>
            </span>
      </a>

      <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
        <i class="bx bx-chevron-left bx-sm align-middle"></i>
      </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">

        <li class="menu-item {{ request()->is('dashboard') ? 'active' : '' }}">
            <a href="/dashboard" class="menu-link">
                <i class='menu-icon tf-icons bx bxs-dashboard'></i>
              <div class="text-truncate" data-i18n="Page 2">Dashboard</div>
            </a>
        </li>

        <li class="menu-item">
            <div style="margin-left: 5%; margin-top: 5%; color: #b4b0c4;">Transactions</div>
        </li>
        <li class="menu-item {{ request()->is('leads') ? 'active' : '' }}">
            <a href="/leads" class="menu-link">
                <i class='menu-icon tf-icons bx bxs-layer-plus'></i>
              <div class="text-truncate" data-i18n="Page 2">Leads</div>
            </a>
        </li>
        <li class="menu-item {{ request()->is('application') ? 'active' : '' }}">
            <a href="/application" class="menu-link">
                <i class='menu-icon tf-icons bx bx-list-plus'></i>
              <div class="text-truncate" data-i18n="Page 2">Application</div>
            </a>
        </li>
        <li class="menu-item {{ request()->is('vehicle-reservation') ? 'active' : '' }}">
            <a href="/vehicle-reservation" class="menu-link">
                <i class='menu-icon tf-icons bx bxs-car'></i>
                <div class="text-truncate" data-i18n="Page 2">Vehicle Reservation</div>
            </a>
        </li>
        <li class="menu-item {{ request()->is('vehicle-releases') ? 'active' : '' }}">
            <a href="/vehicle-releases" class="menu-link">
                <i class='menu-icon tf-icons bx bxs-right-top-arrow-circle'></i>
              <div class="text-truncate" data-i18n="Page 2">Vehicle Releases</div>
            </a>
        </li>
        <li class="menu-item">
            <div style="margin-left: 5%; margin-top: 5%; color: #b4b0c4;">Cancelations</div>
        </li>
        <li class="menu-item {{ request()->is('') ? 'active' : '' }}">
            <a href="" class="menu-link">
                <i class='menu-icon tf-icons bx bxs-x-square'></i>
              <div class="text-truncate" data-i18n="Page 2">Disputes</div>
            </a>
        </li>

        <li class="menu-item">
            <div style="margin-left: 5%; margin-top: 5%; color: #b4b0c4;">Inventory</div>
        </li>
        <li class="menu-item {{ request()->is('vehicle-inventory') ? 'active' : '' }}">
            <a href="/vehicle-inventory" class="menu-link">
                <i class='menu-icon tf-icons bx bxs-car-garage'></i>
              <div class="text-truncate" data-i18n="Page 2">Vehicle Inventory</div>
            </a>
        </li>

        <li class="menu-item">
            <div style="margin-left: 5%; margin-top: 5%; color: #b4b0c4;">Statistics</div>
        </li>
        <li class="menu-item {{ request()->is('') ? 'active' : '' }}">
            <a href="" class="menu-link">
                <i class='menu-icon tf-icons bx bxs-pie-chart-alt-2'></i>
              <div class="text-truncate" data-i18n="Page 2">Sales</div>
            </a>
        </li>

        <li class="menu-item">
            <div style="margin-left: 5%; margin-top: 5%; color: #b4b0c4;">User Management</div>
        </li>
        <li class="menu-item {{ request()->is('') ? 'active' : '' }}">
            <a href="" class="menu-link">
                <i class='menu-icon tf-icons bx bxs-group'></i>
              <div class="text-truncate" data-i18n="Page 2">Users</div>
            </a>
        </li>

      </ul>
  </aside>
