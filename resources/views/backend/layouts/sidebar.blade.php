<aside class="left-sidebar" data-sidebarbg="skin6">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar" data-sidebarbg="skin6">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                <!-- 1. Dashboard -->
                <li class="sidebar-item {{ request()->routeIs('dashboard') ? 'selected' : '' }}">
                    <a class="sidebar-link sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}" aria-expanded="false">
                        <i data-feather="home" class="feather-icon"></i>
                        <span class="hide-menu">Dashboard</span>
                    </a>
                </li>

                <!-- 2. Industries -->
                <li class="sidebar-item {{ request()->routeIs('industries.*') ? 'selected' : '' }}">
                    <a class="sidebar-link sidebar-link {{ request()->routeIs('industries.*') ? 'active' : '' }}" href="{{ route('industries.index') }}" aria-expanded="false">
                        <i data-feather="grid" class="feather-icon"></i>
                        <span class="hide-menu">Industries</span>
                    </a>
                </li>

                <!-- 3. Services -->
                <li class="sidebar-item {{ request()->routeIs('services.*') ? 'selected' : '' }}"">
                    <a class="sidebar-link sidebar-link {{ request()->routeIs('services.*') ? 'active' : '' }}" href="{{ route('services.index') }}" aria-expanded="false">
                        <i data-feather="box" class="feather-icon"></i>
                        <span class="hide-menu">Services</span>
                    </a>
                </li>

                <!-- 4. Workflow Templates -->
                <li class="sidebar-item {{ request()->routeIs('workflow-templates.*') ? 'selected' : '' }}">
                    <a class="sidebar-link sidebar-link {{ request()->routeIs('workflow-templates.*') ? 'active' : '' }}" href="{{ route('workflow-templates.index') }}" aria-expanded="false">
                        <i data-feather="file-text" class="feather-icon"></i>
                        <span class="hide-menu">Workflow Templates</span>
                    </a>
                </li>

                <!-- 5. Vendors -->
                <li class="sidebar-item {{ request()->routeIs('vendors.*') ? 'selected' : '' }}">
                    <a class="sidebar-link sidebar-link {{ request()->routeIs('vendors.*') ? 'active' : '' }}" href="{{ route('vendors.index') }}" aria-expanded="false">
                        <i data-feather="users" class="feather-icon"></i>
                        <span class="hide-menu">Vendors</span>
                    </a>
                </li>

                <!-- 6. Staff & Permissions -->
                <li class="sidebar-item">
                    <a class="sidebar-link sidebar-link" href="javascript:void(0)" aria-expanded="false">
                        <i data-feather="shield" class="feather-icon"></i>
                        <span class="hide-menu">Staff &amp; Permissions</span>
                    </a>
                </li>

                <!-- 7. Orders -->
                <li class="sidebar-item">
                    <a class="sidebar-link sidebar-link" href="javascript:void(0)" aria-expanded="false">
                        <i data-feather="shopping-bag" class="feather-icon"></i>
                        <span class="hide-menu">Orders</span>
                    </a>
                </li>

                <!-- 8. Payments -->
                <li class="sidebar-item">
                    <a class="sidebar-link sidebar-link" href="javascript:void(0)" aria-expanded="false">
                        <i data-feather="credit-card" class="feather-icon"></i>
                        <span class="hide-menu">Payments</span>
                    </a>
                </li>

                <!-- 9. Reports/ Analytics -->
                <li class="sidebar-item">
                    <a class="sidebar-link sidebar-link" href="javascript:void(0)" aria-expanded="false">
                        <i data-feather="bar-chart-2" class="feather-icon"></i>
                        <span class="hide-menu">Reports/ Analytics</span>
                    </a>
                </li>

                <!-- 10. Settings -->
                <li class="sidebar-item">
                    <a class="sidebar-link sidebar-link" href="javascript:void(0)" aria-expanded="false">
                        <i data-feather="settings" class="feather-icon"></i>
                        <span class="hide-menu">Settings</span>
                    </a>
                </li>

                <li class="list-divider"></li>
                <!-- Logout -->
                {{-- <li class="sidebar-item">
                    <a class="sidebar-link sidebar-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('header-logout-form').submit();" aria-expanded="false">
                        <i data-feather="log-out" class="feather-icon"></i>
                        <span class="hide-menu">Logout</span>
                    </a>
                </li> --}}
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>
