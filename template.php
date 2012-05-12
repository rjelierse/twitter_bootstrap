<?php
/**
 * Bootstrap template functions.
 */

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

/**
 * @param $element
 *     The fieldset element.
 *
 * @return string
 *     The rendered fieldset element.
 */
function twitter_bootstrap_fieldset($element)
{
    static $counter = 0;

    // Set fieldset ID.
    if (isset($element['#group']) && !empty($element['#group'])) {
        $element['#collapsed'] = ($counter === 0) ? false : true;
        $id = 'fieldset-' . $element['#group'] . '-' . $counter++;
    }
    else {
        $id = 'fieldset-' . $counter++;
    }

    // Fieldset classes.
    if (isset($element['#attributes']['class'])) {
        $element['#attributes']['class'] = 'accordion-group ' . $element['#attributes']['class'];
    }
    else {
        $element['#attributes']['class'] = 'accordion-group';
    }

    $fieldset_content = isset($element['#children']) ? $element['#children'] . $element['#value'] : $element['#value'];

    $content = '<div' . drupal_attributes($element['#attributes']) . '>';
    if (!empty($element['#title'])) {
        $content .= twitter_bootstrap_fieldset_title($id, $element['#title'], !empty($element['#collapsible']), isset($element['#group']) ? $element['#group'] : '');
    }
    $content .= twitter_bootstrap_fieldset_body($id, $fieldset_content, !empty($element['#collapsible']), !empty($element['#collapsed']));
    $content .= "</div>\n";

    return $content;
}

/**
 * @param string $id
 *     Fieldset body identifier.
 * @param string $label
 *     Label to use as the title.
 * @param bool $collapsible
 *     (optional) If the fieldset is collapsible.
 * @param string $group
 *     (optional) The identifier of the accordeon group, of false if not part of an accordeon.
 *
 * @return string
 *     The rendered fieldset title.
 */
function twitter_bootstrap_fieldset_title($id, $label, $collapsible = false, $group = '')
{
    $attributes = array(
        'class' => 'accordion-heading'
    );

    $link_attributes = array(
        'class' => 'accordion-toggle'
    );

    if ($collapsible) {
        $link_attributes['href'] = '#' . $id;
        $link_attributes['data-toggle'] = 'collapse';
    }

    if ($collapsible && !empty($group)) {
        $link_attributes['data-parent'] = '#' . $group;
    }

    $label = '<a' . drupal_attributes($link_attributes) . '>' . $label . '</a>';

    return '<div' . drupal_attributes($attributes) . '>' . $label . '</div>';
}

/**
 * @param $id
 *     Fieldset body identifier.
 * @param $content
 *     Fieldset content.
 * @param bool $collapsible
 *     (optional) If the fieldset is collapsible.
 * @param bool $collapsed
 *     (optional) If the fieldset is collapsed by default.
 *
 * @return string
 *     The rendered fieldset body.
 */
function twitter_bootstrap_fieldset_body($id, $content, $collapsible = false, $collapsed = false)
{
    $attributes = array(
        'id' => $id,
        'class' => 'accordion-body'
    );

    if ($collapsible) {
        $attributes['class'] .= ' collapse';
    }

    if ($collapsible && !$collapsed) {
        $attributes['class'] .= ' in';
    }

    return '<div' . drupal_attributes($attributes) . '><div class="accordion-inner">' . $content . '</div></div>';
}

/**
 * Build the local tasks for the current page.
 *
 * @return string
 *     The rendered task lists.
 */
function twitter_bootstrap_menu_local_tasks()
{
    $output = '';

    $primary = menu_primary_local_tasks();
    if (!empty($primary)) {
        $output .= implode("\n", array('<ul class="nav nav-tabs nav-tabs-primary">', $primary, '</ul>'));
    }

    $secondary = menu_secondary_local_tasks();
    if (!empty($secondary)) {
        $output .= implode("\n", array('<ul class="nav nav-pills nav-pills-secondary">', $secondary, '</ul>'));
    }

    return $output;
}

/**
 * Theme a form button.
 *
 * @param array $element
 *     The button element.
 *
 * @return string
 *     The rendered button element.
 */
function twitter_bootstrap_button($element)
{
    // Set button type.
    if (isset($element['#attributes']['class'])) {
        $element['#attributes']['class'] = 'form-' . $element['#button_type'] . ' ' . $element['#attributes']['class'];
    }
    else {
        $element['#attributes']['class'] = 'form-' . $element['#button_type'];
    }

    // Set button as such.
    $element['#attributes']['class'] .= ' btn';

    // Build HTML.
    $button = '<button type="submit"';
    if (!empty($element['#name'])) {
        $button .= ' name="' . $element['#name'] . '"';
    }
    $button .= ' id="' . $element['#id'] . '"';
    $button .= drupal_attributes($element['#attributes']);
    $button .= '>';
    $button .= check_plain($element['#value']);
    $button .= "</button>\n";

    return $button;
}

/**
 * Theme a form image button.
 *
 * @param array $element
 *     The button element.
 *
 * @return string
 *     The rendered button element.
 */
function twitter_bootstrap_image_button($element)
{
    // Set button type.
    if (isset($element['#attributes']['class'])) {
        $element['#attributes']['class'] = 'form-' . $element['#button_type'] . ' ' . $element['#attributes']['class'];
    }
    else {
        $element['#attributes']['class'] = 'form-' . $element['#button_type'];
    }

    // Set button as such.
    $element['#attributes']['class'] .= ' btn';

    // Build HTML.
    $button = '<button type="submit"';
    if (!empty($element['#name'])) {
        $button .= ' name="' . $element['#name'] . '"';
    }
    $button .= ' id="' . $element['#id'] . '"';
    $button .= drupal_attributes($element['#attributes']);
    $button .= '>';
    $button .= '<img src="' . base_path() . $element['#src'] . '"';
    if (!empty($element['#value'])) {
        $button .= ' alt="' . check_plain($element['#value']) . '"';
    }
    $button .= "></button>\n";

    return $button;
}

/**
 * Theme a radio input.
 *
 * @param array $element
 *     The radio element.
 *
 * @return string
 *     The rendered radio element.
 */
function twitter_bootstrap_radio($element)
{
    _form_set_class($element, array('form-radio'));
    $output = '<input type="radio" ';
    $output .= 'id="' . $element['#id'] . '" ';
    $output .= 'name="' . $element['#name'] . '" ';
    $output .= 'value="' . $element['#return_value'] . '" ';
    $output .= (check_plain($element['#value']) == $element['#return_value']) ? ' checked="checked" ' : ' ';
    $output .= drupal_attributes($element['#attributes']) . ' />';

    if (!is_null($element['#title'])) {
        $output = '<label class="radio option" for="' . $element['#id'] . '">' . $output . ' ' . $element['#title'] . '</label>';
    }

    unset($element['#title']);
    return theme('form_element', $element, $output);
}

/**
 * Theme a checkbox input.
 *
 * @param array $element
 *     The checkbox element.
 *
 * @return string
 *     The rendered checkbox element.
 */
function twitter_bootstrap_checkbox($element)
{
    _form_set_class($element, array('form-checkbox'));
    $output = '<input type="checkbox" ';
    $output .= 'id="' . $element['#id'] . '" ';
    $output .= 'name="' . $element['#name'] . '" ';
    $output .= 'value="' . $element['#return_value'] . '" ';
    $output .= $element['#value'] ? ' checked="checked" ' : ' ';
    $output .= drupal_attributes($element['#attributes']) . ' />';

    if (!is_null($element['#title'])) {
        $output = '<label class="checkbox option" for="' . $element['#id'] . '">' . $output . ' ' . $element['#title'] . '</label>';
    }

    unset($element['#title']);
    return theme('form_element', $element, $output);
}

/**
 * Theme a text input.
 *
 * @param array $element
 *     The text element.
 *
 * @return string
 *     The rendered text element.
 */
function twitter_bootstrap_textfield($element)
{
    $class = array('form-text');
    $size = empty($element['#size']) ? 60 : $element['#size'];
    $class[] = 'span' . ceil($size / 15);
    $extra = '';
    $output = '';

    if ($element['#autocomplete_path'] && menu_valid_path(array('link_path' => $element['#autocomplete_path']))) {
        drupal_add_js('misc/autocomplete.js');
        $class[] = 'form-autocomplete';
        $extra = '<input class="autocomplete" type="hidden" id="' . $element['#id'] . '-autocomplete" value="' . check_url(url($element['#autocomplete_path'], array('absolute' => TRUE))) . '" disabled="disabled" />';
    }

    _form_set_class($element, $class);

    // Field prefix
    if (isset($element['#field_prefix'])) {
        $output .= '<div class="input-prepend">';
        $output .= '<span class="add-on">' . $element['#field_prefix'] . '</span>';
    }
    // Field suffix
    elseif (isset($element['#field_suffix'])) {
        $output .= '<div class="input-append">';
    }

    $output .= '<input type="text"';
    if (!empty($element['#maxlength'])) {
        $output .= ' maxlenght="' . $element['#maxlength'] . '"';
    }
    $output .= ' name="' . $element['#name'] . '"';
    $output .= ' id="' . $element['#id'] . '"';
    $output .= ' value="' . check_plain($element['#value']) . '"';
    $output .= drupal_attributes($element['#attributes']);
    $output .= '>';

    // Field prefix
    if (isset($element['#field_prefix'])) {
        $output .= '</div>';
    }
    // Field suffix
    elseif (isset($element['#field_suffix'])) {
        $output .= '<span class="add-on">' . $element['#field_suffix'] . '</span>';
        $output .= '</div>';
    }

    return theme('form_element', $element, $output) . $extra;
}

/**
 * Theme a form control.
 *
 * @param array $element
 *     The form element.
 * @param string $value
 *     The form control.
 *
 * @return string
 *     The rendered form control.
 */
function twitter_bootstrap_form_element($element, $value)
{
    // Control wrapper
    $output = '<div class="control-group"';
    if (!empty($element['#id'])) {
        $output .= ' id="' . $element['#id'] . '-wrapper"';
    }
    $output .= ">\n";

    // Control label
    if (!empty($element['#title'])) {
        $output .= '<label class="control-label"';
        if (!empty($element['#id'])) {
            $output .= ' for="' . $element['#id'] . '"';
        }
        $output .= '>';
        $output .= $element['#title'];
        $output .= "</label>\n";
    }

    // Control
    $output .= '<div class="controls">';
    $output .= $value;
    if (!empty($element['#description'])) {
        $output .= '<p class="help-block">' . $element['#description'] . '</p>';
    }
    $output .= "</div>\n";

    $output .= "</div>\n";
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
 * @param string $type
 *     (optional) The type of messages to display.
 *
 * @return string
 *     The rendered system messages.
 */
function twitter_bootstrap_status_messages($type = '')
{
    $output = '';
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
function twitter_bootstrap_mark($type = MARK_NEW)
{
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
 * @param array $element
 *     The filters element.
 *
 * @return string
 *     The rendered filters widget.
 */
function twitter_bootstrap_inline_filters($element)
{
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

    $output .= '<div class="form-actions">'.drupal_render($form['buttons']).'</div>';

    $output .= drupal_render($form);
    return $output;
}