<div class="app-menu">
    <div class="container">
        <ul class="menu-list">
            <li class="active-page">
                @if (Auth::user()->isAdmin())
                    <a href="/admin" class="active">Dashboard</a>
                @elseif (Auth::user()->isSeller())
                    <a href="/seller" class="active">Dashboard</a>
                @endif
            </li>
            <li>
                <a href="#">Apps<i class="material-icons has-sub-menu">keyboard_arrow_down</i></a>
                <ul class="sub-menu">
                    <li>
                        @if (Auth::user()->isAdmin())
                            <a href="/admin/settings">Settings</a>
                        @elseif (Auth::user()->isSeller())
                            <a href="/seller/settings">Settings</a>
                        @endif
                    </li>
                    <li>
                        <form id="form_logout" action="{{ route('logout') }}" method="post">
                            @csrf
                            <a href="javascript:void(0)" onclick="$('#form_logout').submit()">Logout</a>
                        </form>
                    </li>
                </ul>
            </li>
            @if (Auth::user()->isAdmin())
                <li>
                    <a href="#">Accounts<i class="material-icons has-sub-menu">keyboard_arrow_down</i></a>
                    <ul class="sub-menu">
                        <li>
                            <a href="/admin/customers">Customers</a>
                        </li>
                        <li>
                            <a href="/admin/riders">Riders</a>
                        </li>
                        <li>
                            <a href="/admin/sellers">Sellers</a>
                        </li>
                    </ul>
                </li>
            @endif
            @if (Auth::user()->isSeller())
                <li>
                    <a href="#">Pages<i class="material-icons has-sub-menu">keyboard_arrow_down</i></a>
                    <ul class="sub-menu">
                        <li>
                            <a href="#">Test1</a>
                        </li>
                        <li>
                            <a href="#">Test2</a>
                        </li>
                        <li>
                            <a href="#">Authentication<i
                                    class="material-icons has-sub-menu">keyboard_arrow_right</i></a>
                            <ul class="sub-menu">
                                <li>
                                    <a href="#">Test11</a>
                                </li>
                                <li>
                                    <a href="#">Test12</a>
                                </li>
                                <li>
                                    <a href="#">Test13</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="#">Error</a>
                        </li>
                    </ul>
                </li>
            @endif
        </ul>
    </div>
</div>
