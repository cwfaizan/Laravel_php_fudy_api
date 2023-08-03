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
    <div class="app horizontal-menu app-auth-sign-up align-content-stretch d-flex flex-wrap justify-content-end">
        <div class="app-auth-background">

        </div>
        <div class="app-auth-container">
            <div class="logo">
                <a href="/">{{ env('APP_NAME') }}</a>
            </div>
            <p class="auth-description">Please enter your credentials to create an account.<br>Already have an account?
                <a href="/login">Sign In</a>
            </p>
            <form action="{{ route('reset.password') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="auth-credentials m-b-xxl">
                    <input type="hidden" name="user_id" value="{{ $user_id }}">
                    <input type="hidden" name="pin_type" value="{{ $pin_type }}">
                    <label for="signUpPassword" class="form-label">Password</label>
                    <input type="password" name="password"
                        class="form-control @error('password') border border-danger @enderror" id="signUpPassword"
                        aria-describedby="signUpPassword"
                        placeholder="&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;" required>
                    @error('password')
                        <div class="text-danger">
                            {{ $message }}
                        </div>
                    @enderror
                    <div class="form-text">Password must be minimum 8 characters length*</div>

                    <label for="signUpConfirmPassword" class="form-label">Confirm Password</label>
                    <input type="password" name="password_confirmation"
                        class="form-control @error('password_confirmation') border border-danger @enderror"
                        id="signUpConfirmPassword" aria-describedby="signUpConfirmPassword"
                        placeholder="&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;" required>
                    @error('password_confirmation')
                        <div class="text-danger">
                            {{ $message }}
                        </div>
                    @enderror
                    <div class="form-text">Confirm Password must be minimum 8 characters length*</div>
                </div>
                @isset($pin_message)
                    <div class="alert alert-success alert-style-light" role="alert">
                        <strong>Info!</strong> {{ $pin_message }}
                    </div>
                @endisset
                <div class="auth-submit">
                    <button type="submit" class="btn btn-primary">Change Password</button>
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
