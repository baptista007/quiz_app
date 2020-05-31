<?php
// Set url Variable From Router Class
$page_name = Router :: get_page_name();
$page_action = Router :: get_page_action();
$page_id = Router :: get_page_id();

$body_class = "$page_name-" . str_ireplace('index', 'list', $page_action);
$page_title = $this->get_page_title();
?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $page_title; ?></title>
        <meta http-equiv="content-type" content="text/html;charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <link rel="shortcut icon" href="<?php print_link(SITE_FAVICON); ?>" />
        <?php
        Html :: page_meta('theme-color', META_THEME_COLOR);
        Html :: page_meta('author', META_AUTHOR);
        Html :: page_meta('keyword', META_KEYWORDS);
        Html :: page_meta('description', META_DESCRIPTION);
        Html :: page_meta('viewport', META_VIEWPORT);
        Html :: page_css('font-awesome.css');
        Html :: page_css('animate.css');
        Html :: page_css('autocomplete.css');
        //Html :: page_css('bootstrap-default.css');
        Html :: page_css('sb-admin.css');
        //Html :: page_css('custom-style.css');
        Html :: page_css('admin-custom-style.css');
        Html :: page_js('jquery-2.1.4.min.js');
        ?>
    </head>
    <body id="page-top">
        <!-- Page Wrapper -->
        <div id="wrapper">

            <!-- Sidebar -->
            <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

                <!-- Sidebar - Brand -->
                <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= SITE_ADDR ?>admin">
                    <div class="sm-screen-logo">
                        
                    </div>
                    <div class="lg-screen-logo">
                        
                    </div>
                </a>

                <!-- Divider -->
                <hr class="sidebar-divider my-0">

                <!-- Nav Item - Dashboard -->
                <li class="nav-item active">
                    <a class="nav-link" href="<?= SITE_ADDR ?>">
                        <i class="fa fa-dashboard"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <!-- Divider -->
                <hr class="sidebar-divider">
                <li class="nav-item">
                    <a class="nav-link" href="<?= get_link('main/man_quiz') ?>">
                        <i class="fa fa-question"></i> <span>Manage Quizzes</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= get_link('main/man_subject') ?>">
                        <i class="fa fa-book"></i> <span>Manage Subjects</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= get_link('main/man_contributor') ?>">
                        <i class="fa fa-user-plus"></i> <span>Manage Teachers/Contributors</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= get_link('users/') ?>">
                        <i class="fa fa-users"></i> <span>Manage System Users</span>
                    </a>
                </li>
                <?php
//                if (PageAccessManager::is_allowed('users')) {
//                }
                ?>

                <!-- Divider -->
                <hr class="sidebar-divider d-none d-md-block">

                <!-- Sidebar Toggler (Sidebar) -->
                <div class="text-center d-none d-md-inline">
                    <button class="rounded-circle border-0" id="sidebarToggle"></button>
                </div>
            </ul>
            <!-- End of Sidebar -->

            <!-- Content Wrapper -->
            <div id="content-wrapper" class="d-flex flex-column">
                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <h3>
                        <?= (isset($this->page_title) ? $this->page_title : 'Namibia Fun Learning') ?>
                    </h3>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                        </li>


                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo ucwords(USER_NAME); ?></span>
                                <img class="img-profile rounded-circle" src="<?= SITE_ADDR . IMG_DIR . 'avatar.png' ?>">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="<?= get_link('main/logout') ?>">
                                    <i class="fa fa-sign-out"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Main Content -->
                <div id="content">
                    <!-- Page Main Content Start -->
                    <div id="app-body">
                        <div class="container-fluid">
                            <div class="flash-msg-container"><?php show_flash_msg(); ?></div>
                        </div>
                        <?php $this->render_body(); ?>
                    </div>
                </div>

                <!-- Footer -->
                <footer class="sticky-footer bg-white">
                    <div class="container my-auto">
                        <div class="copyright text-center my-auto">
                            <div class="copyright">All rights reserved. &copy; <?php echo SITE_NAME ?> - <?php echo date('Y') ?></div>
                        </div>
                    </div>
                </footer>
                <!-- End of Footer -->
            </div>
        </div>
        <div id="gen-preview-modal" class="modal fade" role="dialog">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Preview SDG Data - CSV</h4>
                    </div>
                    <div class="modal-body">
                        <div id="gen-preview-modal-holder"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning" onclick="closeModal('gen-preview-modal');">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <script>
            var siteAddr = '<?php echo SITE_ADDR; ?>';
            var defaultPageLimit = <?php echo MAX_RECORD_COUNT; ?>;
        </script>
        <?php
        Html :: page_js('bootstrap.js');
        Html :: page_js('jquery.form.min.js');
        Html :: page_js('autocomplete.min.js');
        Html :: page_js('custom.js');
        ?>
        <script type="text/javascript">
            $(function () {
                $('#main-page-modal').on('hidden.bs.modal', function () {
                    $('#main-page-modal-body').html('<img src="<?= SITE_ADDR . IMG_DIR . 'spinner.gif' ?>" alt="Spinner" />');
                });
            });
        </script>
    </body>
</html>