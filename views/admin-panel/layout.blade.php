@include('admin-panel.components._head')

    <body>

        <!-- Begin page -->
        <div id="wrapper">

            <!-- Pre-loader -->
            <div id="preloader">
                <div id="status">
                    <div class="spinner">Loading...</div>
                </div>
            </div>
            <!-- End Preloader-->

            @include('admin-panel.components._side-bar')
                 
            <div class="content-page">

                {{-- Top Navbar --}}
                @include('admin-panel.components._top-bar')

                <!-- Start content -->
                <div class="content">
                    <div class="container-fluid">

                       @include('admin-panel.components._page-header')
                        
                       @yield('page-content')

                    </div> <!-- container -->
                               
                </div> <!-- content -->

                <footer class="footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6">
                                <div>&copy; <?php echo date('Y') ?>. Developed by <a href="https://www.ahmdshan.com" class="text-primary" target="_blank">Ahmed Shan</a></div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-none d-md-flex gap-4 align-item-center justify-content-md-end footer-links">
                                    <a href="javascript: void(0);">About</a>
                                    <a href="javascript: void(0);">Support</a>
                                    <a href="javascript: void(0);">Contact Us</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </footer>

            </div>

        </div>
        <!-- END wrapper -->

        @include('admin-panel.components._js')
        @include('hf.heliumframework-javascript')

    </body>
@include('admin-panel.components._footer')