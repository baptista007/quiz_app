<?php
$navbarsideleft = array(
    array(
        'path' => 'home',
        'label' => 'Home',
        'icon' => ''
    ),
    array(
        'path' => 'usertbl',
        'label' => 'Users',
        'icon' => ''
    ),
    array(
        'path' => 'goaltbl',
        'label' => 'Goals',
        'icon' => ''
    ),
    array(
        'path' => 'indicatortbl',
        'label' => 'Indicators',
        'icon' => ''
    ),
    array(
        'path' => 'updatedata',
        'label' => 'Update Data',
        'icon' => ''
    )
);
?>
<template id="AppHeader">
    <b-navbar toggleable="md" fixed="left" type="dark" variant="light">
        <b-navbar-toggle target="nav_collapse"></b-navbar-toggle>
        <b-navbar-brand href="<?php print_link(""); ?>" class="bg-light">
            <img class="img-responsive" src="<?php print_link(SITE_LOGO); ?>" /> 
        </b-navbar-brand>

        <b-collapse is-nav id="nav_collapse">
            <b-navbar-nav>
                <form>
                    <input class="form-control" type="text" placeholder="Search" aria-label="Search" style="line-height: 34px;">
                </form>
                <div>
                    <ul class="sdg-menu">
                        <li class="accordion-item dropdown" id="accordion">
                            <a href="#" class="item-link item-content link" data-toggle="dropdown">
                                <div class="item-inner">
                                    <div class="item-title">SDGs</div>
                                </div>
                            </a>
                            <ul class="submenu">
                                <?php
                                $db = new PDODb(DB_TYPE, DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME, DB_PORT, DB_CHARSET);
                                $goals = $db->rawQuery("SELECT * FROM \"goals\" ORDER BY \"goal_number\" ASC");

                                foreach ($goals as $goal) {
                                    echo '<li class="accordion-item">
                                                    <a href="#" class="item-link item-content link">
                                                        <div class="item-media">
                                                            <img src="' . SDG_SVG_DIR . $goal["icon"] . '" class="sdg-img">
                                                        </div>
                                                        <div class="item-inner">
                                                            <div class="item-title" style="color: #' . $goal['color'] . '">' . $goal['goal_name'] . '</div>
                                                        </div>
                                                    </a>
                                                </li>';
                                }
                                ?>
                            </ul>
                        </li>
                        <li class="accordion-item">
                            <a href="<?php print_link('main/index'); ?>" class="item-link item-content SDGs National bg-gray">
                                <div class="item-inner">
                                    <div class="item-title">Update Data</div>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
                <?php
                #render_menu($navbarsideleft  , 'left'); 
                ?>
            </b-navbar-nav>
        </b-collapse>

    </b-navbar>
</template>
<script>
    var AppHeader = Vue.component('AppHeader', {
        template: '#AppHeader',
        mounted: function () {
            //let height = this.$el.offsetHeight;
            if (this.$refs.navbar) {
                var height = this.$refs.navbar.offsetHeight;
                document.body.style.paddingTop = height + 'px';

                if (this.$refs.sidebar) {
                    this.$refs.sidebar.style.top = height + 'px';
                }
            }
        }
    });
</script>

<script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>
<script>
    $(function () {
        var Accordion = function (el, multiple) {
            this.el = el || {};
            this.multiple = multiple || false;

            // Variables privadas
            var links = this.el.find('.link');
            // Evento
            links.on('click', {el: this.el, multiple: this.multiple}, this.dropdown)
        }

        Accordion.prototype.dropdown = function (e) {
            var $el = e.data.el;
            $this = $(this),
            $next = $this.next();
            $next.slideToggle();
            $this.parent().toggleClass('open');

            if (!e.data.multiple) {
                $el.find('.submenu').not($next).slideUp().parent().removeClass('open');
            }
            ;
        }

        var accordion = new Accordion($('#accordion'), false);
    });
</script>
<?php

/**
 * Build Menu List From Array
 * Support Multi Level Dropdown Menu Tree
 * Set Active Menu Base on The Current Page || url
 * @return  HTML
 */
function render_menu($arrMenu, $slot = "left") {
    if (!empty($arrMenu)) {
        foreach ($arrMenu as $menuobj) {
            $path = trim($menuobj['path'], "/");

            if (PageAccessManager::GetPageAccess($path) == 'AUTHORIZED') {

                if (empty($menuobj['submenu'])) {
                    ?>
                    <b-nav-item to="/<?php echo ($path); ?>">
                        <?php echo (!empty($menuobj['icon']) ? $menuobj['icon'] : null); ?> 
                        <?php echo $menuobj['label']; ?>
                    </b-nav-item>
                    <?php
                } else {
                    $smenu = $menuobj['submenu'];
                    ?>
                    <b-nav-item-dropdown right>
                        <template slot="button-content">
                            <?php echo (!empty($menuobj['icon']) ? $menuobj['icon'] : null); ?> 
                            <?php echo $menuobj['label']; ?>
                            <?php if (!empty($smenu)) { ?><i class="caret"></i><?php } ?>
                        </template>
                        <?php
                        if (!empty($smenu)) {
                            render_submenu($smenu);
                        }
                        ?>
                    </b-nav-item-dropdown>
                    <?php
                }
            }
        }
    }
}

/**
 * Render Multi Level Dropdown menu 
 * Recursive Function
 * @return  HTML
 */
function render_submenu($arrMenu) {
    if (!empty($arrMenu)) {
        foreach ($arrMenu as $key => $menuobj) {
            $path = trim($menuobj['path'], "/");
            if (PageAccessManager::GetPageAccess($path) == 'AUTHORIZED') {
                ?>
                <b-dropdown-item to="/<?php echo($path); ?>">
                    <?php echo (!empty($menuobj['icon']) ? $menuobj['icon'] : null); ?> 
                    <?php echo $menuobj['label']; ?>
                    <?php
                    if (!empty($menuobj['submenu'])) {
                        render_menu($menuobj['submenu']);
                    }
                    ?>
                </b-dropdown-item>
                <?php
            }
        }
    }
}
?>