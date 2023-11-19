@include('admin-panel.components._head')

<body class="auth-fluid-pages pb-0">

    <div class="auth-fluid">
        <!--Auth fluid left content -->
        <div class="auth-fluid-form-box">
            <div class="align-items-center d-flex h-100">
                <div class="p-3">

                    <!-- Logo -->
                    <div class="auth-brand text-center text-lg-start">
                        <div class="auth-brand">
                            <a href="{{ cpanelPermalink('') }}" class="logo logo-dark text-center">
                                <span class="logo-lg">
                                    <img src="{{ assets('logos/custom_aside.png') }}" alt="" height="22">
                                </span>
                            </a>
        
                            <a href="{{ cpanelPermalink('') }}" class="logo logo-light text-center">
                                <span class="logo-lg">
                                    <img src="{{ assets('logos/custom_aside_dark.png') }}" alt="" height="22">
                                </span>
                            </a>
                        </div>
                    </div>

                    <!-- title-->
                    <h4 class="mt-0">Sign In</h4>
                    <p class="text-muted mb-4">Enter your email and password to access account.</p>

                    <!-- form -->
                    <form class="form-horizontal m-t-20 HFForm" action="{{ cpanelPermalink('login') }}" method="post" data-na="success-then-redirect-to-next-screen-server" >
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input class="form-control" type="email" name="email" id="email" required="" placeholder="Enter your email">
                        </div>
                        <div class="mb-3">
                            <a href="{{ cpanelPermalink('forgot-password') }}" class="text-muted float-end"><small>Forgot your password?</small></a>
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group input-group-merge">
                                <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password">
                            </div>
                        </div>

                        {{ csrf() }}
                        
                        {{-- <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="checkbox-signin">
                                <label class="form-check-label" for="checkbox-signin">Remember me</label>
                            </div>
                        </div> --}}
                        <div class="text-center d-grid">
                            <button class="btn btn-primary" name="submit" id="submit" type="submit">Log In </button>
                        </div>
                        <!-- social-->
                        {{-- <div class="text-center mt-4">
                            <p class="text-muted font-16">Sign in with</p>
                            <ul class="social-list list-inline mt-3">
                                <li class="list-inline-item">
                                    <a href="javascript: void(0);" class="social-list-item border-primary text-primary"><i class="mdi mdi-facebook"></i></a>
                                </li>
                                <li class="list-inline-item">
                                    <a href="javascript: void(0);" class="social-list-item border-danger text-danger"><i class="mdi mdi-google"></i></a>
                                </li>
                                <li class="list-inline-item">
                                    <a href="javascript: void(0);" class="social-list-item border-info text-info"><i class="mdi mdi-twitter"></i></a>
                                </li>
                                <li class="list-inline-item">
                                    <a href="javascript: void(0);" class="social-list-item border-secondary text-secondary"><i class="mdi mdi-github"></i></a>
                                </li>
                            </ul>
                        </div> --}}
                    </form>
                    <!-- end form-->

                    <!-- Footer-->
                    <footer class="footer footer-alt">
                        <p class="text-muted">Don't have an account? <a href="auth-register-2.html" class="text-muted ms-1"><b>Sign Up</b></a></p>
                    </footer>

                </div> <!-- end .card-body -->
            </div> <!-- end .align-items-center.d-flex.h-100-->
        </div>
        <!-- end auth-fluid-form-box-->

        <!-- Auth fluid right content -->
        <div class="auth-fluid-right text-center">
            <div class="auth-user-testimonial">
                <h2 class="mb-3 text-white">Your Smart Home</h2>
                <p class="lead"><i class="mdi mdi-format-quote-open"></i> It's not a faith in technology. It's a faith in people. <i class="mdi mdi-format-quote-close"></i>
                </p>
                <h5 class="text-white">
                    - Steve Jobs
                </h5>
            </div> <!-- end auth-user-testimonial-->
        </div>
        <!-- end Auth fluid right content -->
    </div>
    <!-- end auth-fluid-->

    @include('admin-panel.components._js')
    @include('hf.heliumframework-javascript')

</body>


    
@include('admin-panel.components._footer')