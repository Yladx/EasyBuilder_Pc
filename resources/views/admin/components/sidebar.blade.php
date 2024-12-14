
<!-- Sidebar for navigation -->
<div class="dashboard-sidebar">
    <div class="logo-details">
      <div class="logo_name">Easy Builder PC</div>
      <i class='bx bx-menu' id="btn"></i> <!-- Menu button to toggle sidebar -->
    </div>
    <ul class="nav-list">
        <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <a href="{{ route('admin.dashboard') }}">
                <i ><box-icon name='tachometer' type='solid' color='#fffefe' ></box-icon></i>
                <span class="links_name">Dashboard</span>
            </a>
            <span class="tooltip">Dashboard</span>
        </li>
      <li class="{{ request()->routeIs('builds.index') ? 'active' : '' }}">
        <a href="{{ route('builds.index') }}" id="manageBuildBtn">
            <i class='bx bx-grid-alt'></i>
            <span class="links_name">Manage PC Build</span>
        </a>
        <span class="tooltip">Manage Build</span>
    </li>
    <li class="{{ request()->routeIs('activity-logs.index') ? 'active' : '' }}">
        <a href="{{ route('activity-logs.index') }}" id="activityLogBtn">
            <i class=''><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" >
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M4 12h.01" />
                <path d="M4 6h.01" />
                <path d="M4 18h.01" />
                <path d="M8 18h2" />
                <path d="M8 12h2" />
                <path d="M8 6h2" />
                <path d="M14 6h6" />
                <path d="M14 12h6" />
                <path d="M14 18h6" />
              </svg></i>
            <span class="links_name">Activity Log</span>
        </a>
        <span class="tooltip">Activity Log</span>
    </li>

        <li class="{{ request()->routeIs('users.index') ? 'active' : '' }}">
        <a href="{{ route('users.index') }}" id="userBtn">
            <i><box-icon type='solid' name='user-rectangle'></box-icon></i>
            <span class="links_name">Manage Users</span>
        </a>
        <span class="tooltip">Manage Users</span>
    </li>

    <li class="{{ request()->routeIs('components.index') ? 'active' : '' }}">
        <a href="{{ route('components.index') }}">
            <i>
                <svg xmlns="http://www.w3.org/2000/svg"
                     width="24"
                     height="24"
                     viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="2"
                     stroke-linecap="round"
                     stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M5 5m0 1a1 1 0 0 1 1 -1h12a1 1 0 0 1 1 1v12a1 1 0 0 1 -1 1h-12a1 1 0 0 1 -1 -1z" />
                    <path d="M8 10v-2h2m6 6v2h-2m-4 0h-2v-2m8 -4v-2h-2" />
                    <path d="M3 10h2" />
                    <path d="M3 14h2" />
                    <path d="M10 3v2" />
                    <path d="M14 3v2" />
                    <path d="M21 10h-2" />
                    <path d="M21 14h-2" />
                    <path d="M14 21v-2" />
                    <path d="M10 21v-2" />
                </svg>
            </i>
            <span class="links_name">Pc Part/Component</span>
        </a>
        <span class="tooltip">Pc Part/Component</span>
    </li>

    <li class="{{ request()->routeIs('ads.index') ? 'active' : '' }}">
        <a href="{{ route('ads.index') }}">
            <i >  <svg xmlns="http://www.w3.org/2000/svg" width="27" height="27" viewBox="0 0 24 24" fill="white" class="me-2">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M19 4h-14a3 3 0 0 0 -3 3v10a3 3 0 0 0 3 3h14a3 3 0 0 0 3 -3v-10a3 3 0 0 0 -3 -3zm-10 4a3 3 0 0 1 2.995 2.824l.005 .176v4a1 1 0 0 1 -1.993 .117l-.007 -.117v-1h-2v1a1 1 0 0 1 -1.993 .117l-.007 -.117v-4a3 3 0 0 1 3 -3zm0 2a1 1 0 0 0 -.993 .883l-.007 .117v1h2v-1a1 1 0 0 0 -1 -1zm8 -2a1 1 0 0 1 .993 .883l.007 .117v6a1 1 0 0 1 -.883 .993l-.117 .007h-1.5a2.5 2.5 0 1 1 .326 -4.979l.174 .029v-2.05a1 1 0 0 1 .883 -.993l.117 -.007zm-1.41 5.008l-.09 -.008a.5 .5 0 0 0 -.09 .992l.09 .008h.5v-.5l-.008 -.09a.5 .5 0 0 0 -.318 -.379l-.084 -.023z" />
            </svg></i>
            <span class="links_name">Manage ADS</span>
        </a>

        <li class="{{ request()->routeIs('modules.index') ? 'active' : '' }}">
            <a href="{{ route('modules.index') }}">
            <i class='bx bx-book'></i>
            <span class="links_name">Manage Modules</span>
        </a>
        <span class="tooltip">Modules</span>
    </li>





      <!-- Profile section -->
      <li class="profile">
        <div class="profile-details">
          <div class="name_job">
            <div class="name">ADMIN</div>
            <div class="job">Admin</div>
          </div>
        </div>

        <!-- Logout form -->
        <form id="logoutForm" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
            @csrf
        </form>

        <!-- Logout icon with click event -->
        <i class='bx bx-log-out' id="log_out" ></i>
      </li>
    </ul>
</div>
