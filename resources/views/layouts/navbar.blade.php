<!--begin::Header-->
<nav class="app-header navbar navbar-expand bg-body">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Start Navbar Links-->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                <i class="bi bi-list"></i>
                </a>
            </li>
            <!-- <li class="nav-item d-none d-md-block"><a href="#" class="nav-link">Home</a></li>
            <li class="nav-item d-none d-md-block"><a href="#" class="nav-link">Contact</a></li> -->
        </ul>
        <!--end::Start Navbar Links-->
        <!--begin::End Navbar Links-->
        <ul class="navbar-nav ms-auto">
            <!--begin::Navbar Search-->
            <!-- <li class="nav-item">
                <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                <i class="bi bi-search"></i>
                </a>
            </li> -->
            <!--end::Navbar Search-->
            <!--begin::Fullscreen Toggle-->
            <li class="nav-item">
                <a class="nav-link" href="#" data-lte-toggle="fullscreen">
                <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
                <i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display: none"></i>
                </a>
            </li>
            <!--end::Fullscreen Toggle-->
            <!--begin::User Menu Dropdown-->
            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                <!-- <img
                    src="../../dist/assets/img/user2-160x160.jpg"
                    class="user-image rounded-circle shadow"
                    alt="User Image"
                    /> -->
                <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                    <!--begin::User Image-->
                    <li class="user-header text-bg-primary">
                        <!-- <img
                            src="../../dist/assets/img/user2-160x160.jpg"
                            class="rounded-circle shadow"
                            alt="User Image"
                            /> -->
                        <!-- <p>{{ Auth::user()->id }}</p> -->
                        <div class="card mb-2">
                            <div class="card-body p-2">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr class="align-middle">
                                            <th class="small p-1">Contact person</th>
                                            <td class="small p-1">{{ Auth::user()->organizations?->contact_person ?? '-' }}</td>
                                        </tr>
                                        <tr class="align-middle">
                                            <th class="small p-1">Phone</th>
                                            <td class="small p-1">{{ Auth::user()->organizations?->phone ?? '-' }}</td>
                                        </tr>
                                        <tr class="align-middle">
                                            <th class="small p-1">Organization email</th>
                                            <td class="small p-1">{{ Auth::user()->organizations?->organization_email ?? '-' }}</td>
                                        </tr>
                                        <tr class="align-middle">
                                            <th class="small p-1">CIN</th>
                                            <td class="small p-1">{{ Auth::user()->organizations?->CIN ?? '-' }}</td>
                                        </tr>
                                        <tr class="align-middle">
                                            <th class="small p-1">GST</th>
                                            <td class="small p-1">{{ Auth::user()->organizations?->GST ?? '-' }}</td>
                                        </tr>
                                        <tr class="align-middle">
                                            <th class="small p-1">Financial limit</th>
                                            <td class="small p-1">{{ Auth::user()->organizations?->financial_limit ?? '-' }}</td>
                                        </tr>
                                        <tr class="align-middle">
                                            <th class="small p-1">Member since</th>
                                            <td class="small p-1">{{ \Carbon\Carbon::parse(Auth::user()->created_at)->translatedFormat('d F Y') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </li>
                    <!--end::User Image-->
                    <!--begin::Menu Body-->
                    <!-- <li class="user-body">
                        <div class="row">
                            <div class="col-4 text-center"><a href="#">Followers</a></div>
                            <div class="col-4 text-center"><a href="#">Sales</a></div>
                            <div class="col-4 text-center"><a href="#">Friends</a></div>
                        </div>
                    </li> -->
                    <!--end::Menu Body-->
                    <!--begin::Menu Footer-->
                    <li class="user-footer text-center">
                        <!-- <a href="#" class="btn btn-default btn-flat">Profile</a> -->
                        <a href="{{ route('logout') }}" class="btn btn-danger w-100" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Sign out</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                    <!--end::Menu Footer-->
                </ul>
            </li>
            <!--end::User Menu Dropdown-->
        </ul>
        <!--end::End Navbar Links-->
    </div>
    <!--end::Container-->
</nav>
<!--end::Header-->