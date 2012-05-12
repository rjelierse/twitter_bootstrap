<?php
/**
 * Bootstrap theme: page template.
 */
?><!DOCTYPE html>
<html lang="<?php echo $language->language; ?>" dir="<?php echo $language->dir; ?>">
<head>
    <?php echo $head; ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $head_title; ?></title>
    <?php echo $styles; ?>
    <?php echo $scripts; ?>
</head>
<body data-spy="scroll" data-target=".subnav" data-offset="50" class="<?php echo $body_classes; ?>">
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
                <?php if (!empty($primary_links)): ?>
                <?php echo theme('links', $primary_links, array('class' => 'nav primary-links')); ?>
                <?php endif; ?>

                <!--: Search form :-->
                <?php if (!empty($search_box)): ?>
                <?php echo $search_box; ?>
                <?php endif; ?>

                <ul class="nav pull-right">
                    <?php if ($logged_in): ?>
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#"><?php echo $user->name; ?><b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><?php echo l(t('My profile'), 'user'); ?></li>
                            <li><?php echo l(t('Log out'), 'logout'); ?></li>
                        </ul>
                    </li>
                    <?php else: ?>
                    <li><?php echo l(t('Log in'), 'user'); ?></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>

    <!--= Container =-->
    <div class="container">
        <header class="page-header">
            <h1><?php echo $title; ?></h1>
        </header>
        <div class="row">
            <?php if (!empty($left)): ?>
            <div class="span3" id="sidebar-left">
                <?php echo $left; ?>
            </div>
            <?php endif; ?>

            <div class="<?php echo $content_class; ?>">
                <?php if (!empty($tabs)): ?>
                <!--: Tabbed navigation :-->
                <?php echo $tabs; ?>
                <?php endif; ?>

                <?php if (!empty($help)): ?>
                <!--: Help text :-->
                <div class="well help">
                    <?php echo $help; ?>
                </div>
                <?php endif; ?>

                <?php if (!empty($messages)): ?>
                <!--: Drupal messages :-->
                <?php echo $messages; ?>
                <?php endif; ?>

                <?php echo $content; ?>
            </div>

            <?php if (!empty($right)): ?>
            <div class="span3" id="sidebar-right">
                <?php echo $right; ?>
            </div>
            <?php endif; ?>
        </div>
    </div><!-- /container -->

<?php echo $closure; ?>

</body>
</html>