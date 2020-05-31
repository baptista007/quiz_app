<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <link rel="shortcut icon" href="<?php print_link(SITE_FAVICON); ?>" />
        <?php
        Html :: page_title(SITE_NAME);
        Html :: page_meta('theme-color', META_THEME_COLOR);
        Html :: page_meta('author', META_AUTHOR);
        Html :: page_meta('keyword', META_KEYWORDS);
        Html :: page_meta('description', META_DESCRIPTION);
        Html :: page_meta('viewport', META_VIEWPORT);
        Html :: page_css('font-awesome.min.css');
        Html :: page_css('animate.css');
        Html :: page_css('bootstrap-default.css');
        Html :: page_css('bootstrap-theme-pulse-blue.css');
        Html :: page_css('metismenu.min.css');
        Html :: page_css('custom-style.css');
        Html :: page_css('leaflet.css');
        Html :: page_js('jquery-2.1.4.min.js');
        ?>

        <style>
            #main-content{
                padding:0;
                min-height:80vh;
            }
        </style>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg bg-light navbar-dark fixed-top">
            <a class="navbar-brand" href="<?php print_link('') ?>">
                <img class="img-responsive" src="<?php print_link(SITE_LOGO); ?>" /> 
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
                <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                    <li class="nav-item active">
                        <a class="nav-link" href="<?php print_link('') ?>">Home</a>
                    </li>
                </ul>
            </div>
        </nav>
        <div style="height:70px;"></div>
        <div id="main-content">
            <?php $this->render_body(); ?>
        </div>
        
        <!-- Page Footer Start -->
        <?php Html :: page_footer(); ?>
        <!-- Page Footer Ends -->
    </body>
</html>