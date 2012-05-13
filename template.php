<?php
/**
 * Bootstrap template functions.
 */

require_once dirname(__FILE__).'/template.form.inc';

/**
 * Add theme hooks for the bootstrap theme.
 *
 * @param array $existing
 * @param string $type
 * @param string $theme
 * @param string $page
 *
 * @return array
 *
 * @see hook_theme()
 */
function twitter_bootstrap_theme($existing, $type, $theme, $page)
{
    return array(
        'system_settings_form' => array()
    );
}

/**
 * Preprocessor for page template.
 *
 * This adds the following variables:
 * - $content_class: The class for the content container, based on the page layout.
 *
 * @param array $variables
 *     Page template variables.
 */
function twitter_bootstrap_preprocess_page(&$variables)
{
    // Set layout based on regions.
    if (!empty($variables['page']['left'])) {
        $variables['layout'] = 'left';
    }
    if (!empty($variables['page']['right'])) {
        $variables['layout'] = $variables['layout'] == 'left' ? 'both' : 'right';
    }

    // Set content div class.
    switch ($variables['layout']) {
        case 'both':
            $variables['content_class'] = 'span6';
            break;
        case 'none':
            $variables['content_class'] = 'span12';
            break;
        default:
            $variables['content_class'] = 'span9';
            break;
    }
}

function twitter_bootstrap_menu_local_tasks(&$variables)
{
    $output = '';

    if (!empty($variables['primary'])) {
        $variables['primary']['#prefix'] = '<ul class="nav nav-tabs nav-tabs-primary">';
        $variables['primary']['#suffix'] = '</ul>';
        $output .= drupal_render($variables['primary']);
    }

    if (!empty($variables['secondary'])) {
        $variables['secondary']['#prefix'] = '<ul class="nav nav-pills nav-pills-secondary">';
        $variables['secondary']['#suffix'] = '</ul>';
        $output .= drupal_render($variables['secondary']);
    }

    return $output;
}

function twitter_bootstrap_system_settings_form($form)
{
    // Set primary button.
    $form['buttons']['submit']['#attributes']['class'] = 'btn-primary';

    // Render buttons in action bar.
    $form['buttons']['#value'] = '<div class="form-actions">';
    foreach (element_children($form['buttons']) as $button) {
        $form['buttons']['#value'] .= drupal_render($form['buttons'][$button]);
    }
    $form['buttons']['#value'] .= '</div>';

    return drupal_render($form);
}

/**
 * Theme the system messages.
 *
 * @param array $variables
 *     (optional) The type of messages to display.
 *
 * @return string
 *     The rendered system messages.
 */
function twitter_bootstrap_status_messages($variables)
{
    $output = '';
    $type = $variables['display'];

    foreach (drupal_get_messages($type) as $message_type => $messages) {
        // Set message class
        switch ($message_type) {
            case 'error':
                $message_class = 'alert alert-error';
                break;
            case 'warning':
                $message_class = 'alert';
                break;
            case 'success':
                $message_class = 'alert alert-success';
                break;
            default:
                $message_class = 'alert alert-info';
                break;
        }
        // Add a block for each message.
        foreach ($messages as $message) {
            $output .= '<div class="' . $message_class . '">';
            $output .= $message;
            $output .= "</div>\n";
        }
    }
    return $output;
}

/**
 * Theme a table.
 *
 * @param array $header
 *     Table header fields.
 * @param array $rows
 *     Table rows.
 * @param array $attributes
 *     (optional) Table attributes.
 * @param string $caption
 *     (optional) Table caption.
 *
 * @return string
 *     The rendered table.
 */
function twitter_bootstrap_table($header, $rows, $attributes = array(), $caption = '')
{
    if (isset($attributes['class'])) {
        $attributes['class'] .= ' table table-striped';
    }
    else {
        $attributes['class'] = 'table table-striped';
    }

    $output = '<table' . drupal_attributes($attributes) . ">\n";

    if (!empty($caption)) {
        $output .= '<caption>' . $caption . "</caption>\n";
    }

    // Format the table header:
    if (count($header)) {
        $ts = tablesort_init($header);
        // HTML requires that the thead tag has tr tags in it followed by tbody
        // tags. Using ternary operator to check and see if we have any rows.
        $output .= (count($rows) ? ' <thead><tr>' : ' <tr>');
        foreach ($header as $cell) {
            $cell = tablesort_header($cell, $header, $ts);
            $output .= _theme_table_cell($cell, TRUE);
        }
        // Using ternary operator to close the tags based on whether or not there are rows
        $output .= (count($rows) ? " </tr></thead>\n" : "</tr>\n");
    }
    else {
        $ts = array();
    }

    // Format the table rows:
    if (count($rows)) {
        $output .= "<tbody>\n";
        $flip = array('even' => 'odd', 'odd' => 'even');
        $class = 'even';
        foreach ($rows as $number => $row) {
            $attributes = array();
            $cells = array();

            // Check if we're dealing with a simple or complex row
            if (isset($row['data'])) {
                foreach ($row as $key => $value) {
                    if ($key == 'data') {
                        $cells = $value;
                    }
                    else {
                        $attributes[$key] = $value;
                    }
                }
            }
            else {
                $cells = $row;
            }
            if (count($cells)) {
                // Add odd/even class
                $class = $flip[$class];
                if (isset($attributes['class'])) {
                    $attributes['class'] .= ' ' . $class;
                }
                else {
                    $attributes['class'] = $class;
                }

                // Build row
                $output .= ' <tr' . drupal_attributes($attributes) . '>';
                $i = 0;
                foreach ($cells as $cell) {
                    $cell = tablesort_cell($cell, $header, $ts, $i++);
                    $output .= _theme_table_cell($cell);
                }
                $output .= " </tr>\n";
            }
        }
        $output .= "</tbody>\n";
    }

    $output .= "</table>\n";
    return $output;
}

/**
 * Theme a mark.
 *
 * @param int $type
 *     The type of mark.
 *
 * @return string
 *     The rendered mark.
 */
function twitter_bootstrap_mark($variables)
{
    $type = $variables['type'];
    global $user;

    if ($user->uid) {
        if ($type == MARK_NEW) {
            return ' <span class="label label-success">' . t('new') . '</span>';
        }
        else if ($type == MARK_UPDATED) {
            return ' <span class="label label-info">' . t('updated') . '</span>';
        }
    }

    return '';
}

/**
 * Build the user filter widget.
 *
 * @param array $element
 *
 * @return string
 *
 * @see theme_user_filters()
 */
function twitter_bootstrap_user_filters($element)
{
    return twitter_bootstrap_inline_filters($element);
}

/**
 * Build the node filter widget.
 *
 * @param array $element
 *
 * @return string
 *
 * @see theme_node_filters()
 */
function twitter_bootstrap_node_filters($element)
{
    return twitter_bootstrap_inline_filters($element);
}

/**
 * Inline filters widget.
 *
 * @param array $variables
 *     An associative array containing all variables for this theme function:
 *       - $element: The filters element.
 *
 * @return string
 *     The rendered filters widget.
 */
function twitter_bootstrap_inline_filters($variables)
{
    $element = $variables['element'];
    $output = '<ul class="unstyled filter-inline">';

    // Render applied filters first.
    if (isset($element['current']) && is_array($element['current'])) {
        foreach (element_children($element['current']) as $filter) {
            $output .= '<li>' . drupal_render($element['current'][$filter]) . '</li>';
        }
    }

    // Render available filters.
    $output .= '<li><ul class="unstyled horizontal clearfix">';
    if (isset($element['current']) && is_array($element['current'])) {
        $output .= '<li><em>' . t('and') . '</em> ' . t('where') . '</li>';
    }
    $output .= '<li class="filter">';
    foreach (element_children($element['filter']) as $filter) {
        $output .= drupal_render($element['filter'][$filter]);
    }
    $output .= '</li>';
    $output .= '<li class="binder">' . t('is') . '</li>';
    $output .= '<li class="status">';
    foreach (element_children($element['status']) as $filter) {
        $output .= drupal_render($element['status'][$filter]);
    }
    $output .= '</li>';
    $output .= '<li class="buttons">';
    $output .= drupal_render($element['buttons']);
    $output .= '</li>';
    $output .= '</ul></li>';

    $output .= '</ul>';

    return $output;
}

/**
 * @param array $form
 *     The node admin form.
 *
 * @return string
 *     The rendered node admin form.
 */
function twitter_bootstrap_node_admin_nodes($form)
{
    // If there are rows in this form, then $form['title'] contains a list of
    // the title form elements.
    $has_posts = isset($form['title']) && is_array($form['title']);
    $select_header = $has_posts ? theme('table_select_header_cell') : '';
    $header = array($select_header, t('Title'), t('Type'), t('Author'), t('Status'));
    if (isset($form['language'])) {
        $header[] = t('Language');
    }
    $header[] = t('Operations');
    $rows = array();
    $output = '';

    // Render the node actions fieldset.
    unset($form['options']['#prefix']);
    unset($form['options']['#suffix']);
    $form['options']['#attributes']['class'] = 'form-inline';
    $output .= drupal_render($form['options']);

    // Render nodes to table rows.
    if ($has_posts) {
        foreach (element_children($form['title']) as $key) {
            $row = array();
            $row[] = drupal_render($form['nodes'][$key]);
            $row[] = drupal_render($form['title'][$key]);
            $row[] = drupal_render($form['name'][$key]);
            $row[] = drupal_render($form['username'][$key]);
            $row[] = drupal_render($form['status'][$key]);
            if (isset($form['language'])) {
                $row[] = drupal_render($form['language'][$key]);
            }
            $row[] = drupal_render($form['operations'][$key]);
            $rows[] = $row;
        }

    }
    else {
        $rows[] = array(array(
            'data' => t('No posts available.'),
            'colspan' => '6',
        ));
    }

    // Render the nodes table.
    $output .= theme('table', $header, $rows, array('class' => 'table-bordered table-multiselect'));
    if ($form['pager']['#value']) {
        $output .= drupal_render($form['pager']);
    }

    $output .= drupal_render($form);

    return $output;
}

function twitter_bootstrap_user_admin_account($form)
{
    // Overview table:
    $header = array(
        theme('table_select_header_cell'),
        array(
            'data' => t('Username'),
            'field' => 'u.name',
        ),
        array(
            'data' => t('Status'),
            'field' => 'u.status',
        ),
        t('Roles'),
        array(
            'data' => t('Member for'),
            'field' => 'u.created',
            'sort' => 'desc',
        ),
        array(
            'data' => t('Last access'),
            'field' => 'u.access',
        ),
        t('Operations'),
    );
    $rows = array();

    unset($form['options']['#prefix']);
    unset($form['options']['#suffix']);
    $form['options']['#attributes']['class'] = 'form-inline';
    $output = drupal_render($form['options']);

    if (isset($form['name']) && is_array($form['name'])) {
        foreach (element_children($form['name']) as $key) {
            $rows[] = array(
                drupal_render($form['accounts'][$key]),
                drupal_render($form['name'][$key]),
                drupal_render($form['status'][$key]),
                drupal_render($form['roles'][$key]),
                drupal_render($form['member_for'][$key]),
                drupal_render($form['last_access'][$key]),
                drupal_render($form['operations'][$key]),
            );
        }
    }
    else {
        $rows[] = array(array(
            'data' => t('No users available.'),
            'colspan' => '7',
        ));
    }

    $output .= theme('table', $header, $rows, array('class' => 'table-bordered table-multiselect'));
    if ($form['pager']['#value']) {
        $output .= drupal_render($form['pager']);
    }

    $output .= drupal_render($form);

    return $output;
}

/**
 * Render the modules form.
 *
 * @param array $form
 *
 * @return string
 *
 * @see theme_system_modules()
 */
function twitter_bootstrap_system_modules($form)
{
    if (isset($form['confirm'])) {
        return drupal_render($form);
    }

    // Individual table headers.
    $header = array();
    $header[] = array('data' => t('Enabled'), 'class' => 'checkbox');
    if (module_exists('throttle')) {
        $header[] = array('data' => t('Throttle'), 'class' => 'checkbox');
    }
    $header[] = t('Name');
    $header[] = t('Version');
    $header[] = t('Description');

    // Pull package information from module list and start grouping modules.
    $modules = $form['validation_modules']['#value'];
    foreach ($modules as $module) {
        if (!isset($module->info['package']) || !$module->info['package']) {
            $module->info['package'] = t('Other');
        }
        $packages[$module->info['package']][$module->name] = $module->info;
    }
    ksort($packages);

    // Display packages.
    $output = '<div class="accordion" id="packages">';
    foreach ($packages as $package => $modules) {
        $rows = array();
        foreach ($modules as $key => $module) {
            $row = array();
            $description = drupal_render($form['description'][$key]);
            if (isset($form['status']['#incompatible_modules_core'][$key])) {
                unset($form['status'][$key]);
                $status = theme('image', 'misc/watchdog-error.png', t('incompatible'), t('Incompatible with this version of Drupal core'));
                $description .= '<div class="incompatible">' . t('This version is incompatible with the !core_version version of Drupal core.', array('!core_version' => VERSION)) . '</div>';
            }
            elseif (isset($form['status']['#incompatible_modules_php'][$key])) {
                unset($form['status'][$key]);
                $status = theme('image', 'misc/watchdog-error.png', t('incompatible'), t('Incompatible with this version of PHP'));
                $php_required = $form['status']['#incompatible_modules_php'][$key];
                if (substr_count($php_required, '.') < 2) {
                    $php_required .= '.*';
                }
                $description .= '<div class="incompatible">' . t('This module requires PHP version @php_required and is incompatible with PHP version !php_version.', array('@php_required' => $php_required, '!php_version' => phpversion())) . '</div>';
            }
            else {
                $status = drupal_render($form['status'][$key]);
            }
            $row[] = array('data' => $status, 'class' => 'checkbox');
            if (module_exists('throttle')) {
                $row[] = array('data' => drupal_render($form['throttle'][$key]), 'class' => 'checkbox');
            }

            // Add labels only when there is also a checkbox.
            if (isset($form['status'][$key])) {
                $row[] = '<strong><label for="' . $form['status'][$key]['#id'] . '">' . drupal_render($form['name'][$key]) . '</label></strong>';
            }
            else {
                $row[] = '<strong>' . drupal_render($form['name'][$key]) . '</strong>';
            }

            $row[] = array('data' => drupal_render($form['version'][$key]), 'class' => 'version');
            $row[] = array('data' => $description, 'class' => 'description');
            $rows[] = $row;
        }
        $fieldset = array(
            '#title' => t($package),
            '#collapsible' => TRUE,
            '#collapsed' => ($package == 'Core - required'),
            '#value' => theme('table', $header, $rows, array('class' => 'package')),
            '#group' => 'packages'
        );
        $output .= theme('fieldset', $fieldset);
    }

    $output .= '</div>';

    $output .= '<div class="form-actions">' . drupal_render($form['buttons']) . '</div>';

    $output .= drupal_render($form);
    return $output;
}