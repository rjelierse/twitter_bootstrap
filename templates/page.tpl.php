<?php
/**
 * Bootstrap page template.
 */
?>
    <!--= Navigation bar =-->
    <div class="navbar navbar-fixed-top">
        <div class="navbar-inner">
            <div class="container">
                <!--: Branding :-->
                <a class="brand" href="<?php echo $front_page; ?>" rel="home" title="<?php echo t('Home'); ?>">
                <?php if (!empty($logo)): ?>
                    <img src="<?php echo $logo; ?>" alt="<?php echo t('Home'); ?>">
                <?php else: ?>
                    <?php echo $site_name; ?>
                <?php endif; ?>
                </a>

                <!--: Navigation links :-->
                <?php if (!empty($main_menu)): ?>
                <?php echo theme('links', array('links' => $main_menu, 'attributes' => array('class' => 'nav'))); ?>
                <?php endif; ?>

                <ul class="nav pull-right">
                    <?php if ($logged_in && !empty($secondary_menu)): ?>
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#"><?php echo $user->name; ?><b class="caret"></b></a>
                        <?php echo theme('links', array('links' => $secondary_menu, 'attributes' => array('class' => 'dropdown-menu'))); ?>
                    </li>
                    <?php else: ?>
                    <li><?php echo l(t('Log in'), 'user'); ?></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>

    <!--= Container =-->
    <div class="container" id="site-wrapper">
        <header class="page-header">
            <h1><?php echo $title; ?></h1>
        </header>
        <div class="row">
            <?php if (!empty($page['left'])): ?>
            <div class="span3" id="sidebar-left">
                <?php render($page['left']); ?>
            </div>
            <?php endif; ?>

            <div class="<?php echo $content_class; ?>">
                <?php if (!empty($tabs)): ?>
                <!--: Tabbed navigation :-->
                <?php render($tabs); ?>
                <?php endif; ?>

                <?php if (!empty($page['help'])): ?>
                <!--: Help text :-->
                <div class="well help">
                    <?php render($page['help']); ?>
                </div>
                <?php endif; ?>

                <?php if ($show_messages): ?>
                <!--: Drupal messages :-->
                <?php echo $messages; ?>
                <?php endif; ?>

                <?php echo render($page['content']); ?>
            </div>

            <?php if (!empty($page['right'])): ?>
            <div class="span3" id="sidebar-right">
                <?php render($page['right']); ?>
            </div>
            <?php endif; ?>
        </div>
    </div><!-- /container -->