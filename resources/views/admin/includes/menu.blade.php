<nav class="sidebar-nav nav-full">
    <ul class="navbar-nav">
            <li class="nav-item">
                <a 
                    href="{{ route('dashboard') }}"
                    title="Dashbaord">
                    <img class="svg m-3" src="{{ asset('assets/admin/images/cms/dashboard.svg') }}" alt="Dashbaord"/>
                    <span class="menu-title">Dashbaord</span>
                </a>
            </li>
            <li class="nav-item">
                <a 
                    href="{{ route('user.index') }}"
                    title="Users">
                    <img class="svg m-3" src="{{ asset('assets/admin/images/cms/user.svg') }}" alt="Users"/>
                    <span class="menu-title">Users</span>
                </a>
            </li>
    </ul>    
</nav>