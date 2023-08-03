<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Responsive Admin Dashboard Template">
    <meta name="keywords" content="admin,dashboard">
    <meta name="author" content="stacks">
    <!-- The above 6 meta tags *must* come first in the head; any other head content must come *after* these tags -->

    <!-- Title -->
    <title>{{ env('APP_NAME') }}</title>

    <!-- Styles -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Two+Tone|Material+Icons+Round|Material+Icons+Sharp"
        rel="stylesheet">
    <link href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/perfectscroll/perfect-scrollbar.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/pace/pace.css') }}" rel="stylesheet">


    <!-- Theme Styles -->
    <link href="{{ asset('assets/css/main.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/horizontal-menu/horizontal-menu.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet">

    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/images/neptune.png') }}" />
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/images/neptune.png') }}" />

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>
    <div class="app horizontal-menu app-auth-sign-in align-content-stretch d-flex flex-wrap justify-content-end">
        <div class="app-auth-background">

        </div>
        <div class="app-auth-container">
            <div class="logo">
                <a href="/">{{ env('APP_NAME') }}</a>
            </div>
            <p class="auth-description">Please sign-in to your account and continue to the dashboard.<br>Don't have an
                account? <a href="/signup">Sign Up</a></p>
            <form action="{{ route('login') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="auth-credentials m-b-xxl">
                    <label for="signInContact" class="form-label">Contact No</label>
                    <input type="number" name="contact_no" value="{{ old('contact_no') }}"
                        class="form-control m-b-md @error('contact_no') border border-danger @enderror"
                        id="signInContact" aria-describedby="signInContact" placeholder="923001234567" required>
                    @error('contact_no')
                        <div class="text-danger">
                            {{ $message }}
                        </div>
                    @enderror

                    <label for="signInPassword" class="form-label">Password</label>
                    <input type="password" name="password"
                        class="form-control @error('password') border border-danger @enderror" id="signInPassword"
                        aria-describedby="signInPassword"
                        placeholder="&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;" required>
                    @error('password')
                        <div class="text-danger">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                @if (session('status'))
                    <div class="alert alert-success alert-style-light" role="alert">
                        <strong>Info!</strong> {{ session('status') }}
                    </div>
                @endif
                @if (session('status_error'))
                    <div class="alert alert-danger alert-style-light" role="alert">
                        <strong>Warning!</strong> {{ session('status_error') }}
                    </div>
                @endif
                <div class="auth-submit">
                    <button type="submit" class="btn btn-primary">Sign in</button>
                    <a href="/forgot-password" class="auth-forgot-password float-end">Forgot password?</a>
                </div>
            </form>
            <div class="divider"></div>
        </div>
    </div>

    <!-- Javascripts -->
    <script src="{{ asset('assets/plugins/jquery/jquery-3.5.1.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/perfectscroll/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/pace/pace.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>
</body>

</html>
