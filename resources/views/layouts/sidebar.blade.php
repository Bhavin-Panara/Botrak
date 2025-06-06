<!--begin::Sidebar-->
<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <!--begin::Sidebar Brand-->
    <div class="sidebar-brand">
        <!--begin::Brand Link-->
        <a href="{{ route('dashboard') }}" class="brand-link">
            <!--begin::Brand Image-->
            <!-- <img
                src="../../dist/assets/img/AdminLTELogo.png"
                alt="BoTrak Logo"
                class="brand-image opacity-75 shadow"
                /> -->
            <!--end::Brand Image-->
            <!--begin::Brand Text-->
            <span class="brand-text"><b>Bo</b>Trak</span>
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
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->segment(1) == 'dashboard' ? 'active' : '' }}">
                        <i class="nav-icon bi bi-speedometer"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <!-- <li class="nav-item">
                    <a href="{{ route('user.index') }}" class="nav-link {{ request()->segment(1) == 'user' ? 'active' : '' }}">
                        <i class="nav-icon bi bi-people"></i>
                        <p>Users</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('asset.index') }}" class="nav-link {{ request()->segment(1) == 'asset' ? 'active' : '' }}">
                        <i class="nav-icon bi bi-person-vcard-fill"></i>
                        <p>Total Asset</p>
                    </a>
                </li> -->

                <li class="nav-item">
                    <a href="{{ route('organization.index') }}" class="nav-link {{ request()->segment(1) == 'organization' ? 'active' : '' }}">
                        <i class="nav-icon bi bi-building"></i>
                        <p>Organizations</p>
                    </a>
                </li>

                <li class="nav-header">Price Plan Management</li>
                <li class="nav-item">
                    <a href="{{ route('price_plans.index') }}" class="nav-link {{ request()->segment(1) == 'price_plans' ? 'active' : '' }}">
                        <i class="nav-icon bi bi-clipboard-fill"></i>
                        <p>Price Plans</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('company_priceplan.index') }}" class="nav-link {{ request()->segment(1) == 'company_priceplan' ? 'active' : '' }}">
                        <i class="nav-icon bi bi-buildings-fill"></i>
                        <p>Organization Price Plan</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('invoice.index') }}" class="nav-link {{ request()->segment(1) == 'invoice' ? 'active' : '' }}">
                        <i class="nav-icon bi bi-receipt"></i>
                        <p>Invoice</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('report.index') }}" class="nav-link {{ request()->segment(1) == 'report' ? 'active' : '' }}">
                        <i class="nav-icon bi bi-clipboard-data"></i>
                        <p>Report</p>
                    </a>
                </li>

                <!-- <i class="nav-icon bi bi-palette"></i>
                <i class="nav-icon bi bi-box-seam-fill"></i>
                <i class="nav-icon bi bi-circle"></i>
                <i class="nav-icon bi bi-clipboard-fill"></i>
                <i class="nav-arrow bi bi-chevron-right"></i>
                <i class="nav-icon bi bi-tree-fill"></i>
                <i class="nav-icon bi bi-pencil-square"></i>
                <i class="nav-icon bi bi-table"></i>
                <i class="nav-icon bi bi-box-arrow-in-right"></i>
                <i class="nav-icon bi bi-download"></i>
                <i class="nav-icon bi bi-grip-horizontal"></i>
                <i class="nav-icon bi bi-star-half"></i>
                <i class="nav-icon bi bi-ui-checks-grid"></i>
                <i class="nav-icon bi bi-filetype-js"></i>
                <i class="nav-icon bi bi-browser-edge"></i>
                <i class="nav-icon bi bi-hand-thumbs-up-fill"></i>
                <i class="nav-icon bi bi-question-circle-fill"></i>
                <i class="nav-icon bi bi-patch-check-fill"></i>
                <i class="nav-icon bi bi-record-circle-fill"></i>
                <i class="nav-icon bi bi-circle-fill"></i>
                <i class="nav-icon bi bi-circle text-danger"></i>
                <i class="nav-icon bi bi-circle text-warning"></i>
                <i class="nav-icon bi bi-circle text-info"></i> -->
            </ul>
            <!--end::Sidebar Menu-->
        </nav>
    </div>
    <!--end::Sidebar Wrapper-->
</aside>
<!--end::Sidebar-->