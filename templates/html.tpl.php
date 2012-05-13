<?php
/**
 * Bootstrap HTML template.
 */
?><!DOCTYPE html>
<html lang="<?php echo $language->language; ?>" dir="<?php echo $language->dir; ?>"<?php print $rdf_namespaces; ?>>
<head profile="<?php print $grddl_profile; ?>">
    <?php echo $head; ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $head_title; ?></title>
    <?php echo $styles; ?>
    <?php echo $scripts; ?>
</head>
<body class="<?php print $classes; ?>" <?php print $attributes;?>>
    <?php print $page_top; ?>
    <?php print $page; ?>
    <?php print $page_bottom; ?>
</body>
</html>