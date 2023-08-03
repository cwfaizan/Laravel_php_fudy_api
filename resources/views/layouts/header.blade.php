<div class="app-header">
    <nav class="navbar navbar-light navbar-expand-lg container">
        <div class="container-fluid">
            <div class="navbar-nav" id="navbarNav">
                <div class="logo">
                    @if (Auth::user()->isAdmin())
                        <a href="/admin">{{ env('APP_NAME') }}</a>
                    @elseif (Auth::user()->isSeller())
                        <a href="/seller">{{ env('APP_NAME') }}</a>
                    @endif

                </div>
            </div>
            <div class="d-flex">
                <ul class="navbar-nav">
                    <li class="nav-item hidden-on-mobile">
                        <a class="nav-link language-dropdown-toggle" href="#" id="languageDropDown"
                            data-bs-toggle="dropdown">
                            @if (Auth::user()->profile_url == 'NO-IMAGE')
                                Welcome back {{ Auth::user()->last_name }}
                            @else
                                <img src="{{ Auth::user()->profile_url }}" alt="loading">
                            @endif

                        </a>
                        <ul class="dropdown-menu dropdown-menu-end language-dropdown"
                            aria-labelledby="languageDropDown">
                            <li><a class="dropdown-item" href="/admin/settings">Settings</a></li>
                            <li>
                                <form id="form_logout" action="{{ route('logout') }}" method="post">
                                    @csrf
                                    <a href="javascript:void(0)" class="dropdown-item"
                                        onclick="$('#form_logout').submit()">Logout</a>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</div>
