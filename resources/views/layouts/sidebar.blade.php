<nav id="sidebar" class="sidebar">
    <a class="sidebar-brand" href="{{ route('home.show-dashboard') }}">
        <svg>
            <use xlink:href="#ion-ios-pulse-strong"></use>
        </svg>
        Spark
    </a>
    <div class="sidebar-content">
        <div class="sidebar-user">
            <img src="{{ asset('ui-kit/img/logo.jpg') }}" class="img-fluid rounded-circle mb-2" alt="Linda Miller" />
            <div class="font-weight-bold">{{ Auth::user()->name }}</div>
            <small>{{ Auth::user()->email }}</small>
        </div>

        <ul class="sidebar-nav">
            <li class="sidebar-item active">
                <a href="{{ route('home.show-dashboard') }}" class="sidebar-link">
                    <i class="align-middle mr-2 fas fa-fw fa-home"></i> <span class="align-middle">Dashboard</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="#properties" data-toggle="collapse" class="sidebar-link collapsed">
                    <i class="align-middle mr-2 fas fa-fw fa-building"></i> <span class="align-middle">Manage Company</span>
                </a>
                <ul id="properties" class="sidebar-dropdown list-unstyled collapse" data-parent="#sidebar">
                    <li class="sidebar-item"><a class="sidebar-link" href="#">Company Profile </a></li>
                    <li class="sidebar-item"><a class="sidebar-link" href="#">Company Users</a></li>
                    <li class="sidebar-item"><a class="sidebar-link" href="#">Company Wallet </a></li>
                </ul>
            </li>
            <li class="sidebar-item">
                <a href="#" class="sidebar-link">
                    <i class="align-middle mr-2 fas fa-fw fa-car"></i> <span class="align-middle">Manage Vehicles</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="#cs" data-toggle="collapse" class="sidebar-link collapsed">
                    <i class="align-middle mr-2 fas fa-fw fa-headphones"></i> <span class="align-middle">Customer Support</span>
                </a>
                <ul id="cs" class="sidebar-dropdown list-unstyled collapse" data-parent="#sidebar">
                    <li class="sidebar-item"><a class="sidebar-link" href="#">Inquiries </a></li>
                </ul>
            </li>
            <li class="sidebar-item">
                <a href="#company" data-toggle="collapse" class="sidebar-link collapsed">
                    <i class="align-middle mr-2 fas fa-fw fa-user-cog"></i> <span class="align-middle">Account Settings</span>
                </a>
                <ul id="company" class="sidebar-dropdown list-unstyled collapse" data-parent="#sidebar">
                    <li class="sidebar-item"><a class="sidebar-link" href="#">Authentication </a></li>
                </ul>
            </li>
            <li class="sidebar-item">
                <a href="#admin" data-toggle="collapse" class="sidebar-link collapsed">
                    <i class="align-middle mr-2 fas fa-fw fa-lock"></i> <span class="align-middle">Administration</span>
                </a>
                <ul id="admin" class="sidebar-dropdown list-unstyled collapse" data-parent="#sidebar">
                    <li class="sidebar-item"><a class="sidebar-link" href="#">Companies</a></li>
                    <li class="sidebar-item"><a class="sidebar-link" href="#">Vehicles</a></li>
                    <li class="sidebar-item"><a class="sidebar-link" href="#">Users</a></li>
                    <li class="sidebar-item"><a class="sidebar-link" href="#">Payments</a></li>
                    <li class="sidebar-item"><a class="sidebar-link" href="#">Custom Settings</a></li>
                </ul>
            </li>
            <li class="sidebar-item">
                <a href="#metadata" data-toggle="collapse" class="sidebar-link collapsed">
                    <i class="align-middle mr-2 fas fa-fw fa-search"></i> <span class="align-middle">App Metadata</span>
                </a>
                <ul id="metadata" class="sidebar-dropdown list-unstyled collapse" data-parent="#sidebar">
                    <li class="sidebar-item"><a class="sidebar-link" href="#">Counties</a></li>
                    <li class="sidebar-item"><a class="sidebar-link" href="#">Body Types</a></li>
                    <li class="sidebar-item"><a class="sidebar-link" href="#">Drive Setups</a></li>
                    <li class="sidebar-item"><a class="sidebar-link" href="#">Drive Types</a></li>
                    <li class="sidebar-item"><a class="sidebar-link" href="#">Makes & Models</a></li>
                    <li class="sidebar-item"><a class="sidebar-link" href="#">Transmission Types</a></li>
                    <li class="sidebar-item"><a class="sidebar-link" href="#">Vehicle Conditions</a></li>
                </ul>
            </li>
            <li class="sidebar-item">
                <a href="#" class="sidebar-link">
                    <i class="align-middle mr-2 fas fa-fw fa-power-off"></i> <span class="align-middle">Logout (username)</span>
                </a>
            </li>
        </ul>
    </div>
</nav>
