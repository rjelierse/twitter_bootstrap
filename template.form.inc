<?php
/**
 * Bootstrap template functions: Form elements.
 */

/**
 * Theme fieldset elements.
 *
 * @param array $variables
 *     An associative array containing the following elements:
 *       - $element: An associative array containing the properties of the element.
 *                   Properties used: #attributes, #children, #collapsed, #collapsible,
 *                                    #description, #id, #title, #value, #group.
 *
 * @return string
 *     The rendered fieldset element.
 */
function twitter_bootstrap_fieldset($variables)
{
    static $counter = 0;
    $target = 'fieldset-' . $counter++;

    // Fieldset
    $element = $variables['element'];

    // Disable collapsible fieldsets if no title is set.
    if (empty($element['#title'])) {
        $element['#collapsible'] = false;
        $element['#collpased'] = false;
    }

    // Fieldset toggle
    $toggle['#attributes'] = array(
        'class' => 'accordion-toggle',
        'data-toggle' => 'collapse',
        'data-target' => '#' . $target
    );
    if (!empty($element['#group'])) {
        $toggle['#attributes']['data-parent'] = '#' . $element['#group'];
        // First element should be opened, others collapsed.
        $element['#collapsed'] = ($counter === 1) ? false : true;
    }

    // Fieldset body
    $body['#attributes'] = array(
        'class' => 'accordion-body',
        'id' => $target
    );
    if ($element['#collapsible']) {
        $body['#attributes']['class'] .= ' collapse';
    }
    if ($element['#collapsible'] && !$element['#collapsed']) {
        $body['#attributes']['class'] .= ' in';
    }

    element_set_attributes($element, array('id'));
    _form_set_class($element, array('accordion-group'));

    $content = '<div' . drupal_attributes($element['#attributes']) . '>';
    if (!empty($element['#title'])) {
        $content .= '<div class="accordion-heading"><div ' . drupal_attributes($toggle['#attributes']) . '>' . $element['#title'] . '</div></div>';
    }
    $content .= '<div' . drupal_attributes($body['#attributes']) . '><div class="accordion-inner">';
    if (!empty($element['#description'])) {
        $content .= '<div class="help-block">' . $element['#description'] . '</div>';
    }
    $content .= $element['#children'];
    if (!empty($element['#value'])) {
        $content .= $element['#value'];
    }
    $content .= "</div></div></div>\n";

    return $content;
}

/**
 * Theme a form element control.
 *
 * @param array $variables
 *
 * @return string
 *     The rendered form control.
 *
 * @see theme_form_element()
 */
function twitter_bootstrap_form_element($variables)
{
    $element = &$variables['element'];

    // Check if element is part of a group (e.g. generated from #type 'radios' or 'checkboxes')
    $parent = count($element['#array_parents']) - 1;
    if ($element['#array_parents'][$parent] != $element['#name']) {
        return $element['#children'];
    }

    // This function is invoked as theme wrapper, but the rendered form element
    // may not necessarily have been processed by form_builder().
    $element += array(
        '#title_display' => 'before',
    );

    // Add element #id for #type 'item'.
    if (isset($element['#markup']) && !empty($element['#id'])) {
        $attributes['id'] = $element['#id'];
    }
    // Add element's #type and #name as class to aid with JS/CSS selectors.
    $attributes['class'] = array('form-item', 'control-group');
    if (!empty($element['#attributes']['type'])) {
        $attributes['class'][] = 'form-type-' . strtr($element['#attributes']['type'], '_', '-');
    }
    if (!empty($element['#name'])) {
        $attributes['class'][] = 'form-item-' . strtr($element['#name'], array(' ' => '-', '_' => '-', '[' => '-', ']' => ''));
    }
    // Add a class for disabled elements to facilitate cross-browser styling.
    if (!empty($element['#attributes']['disabled'])) {
        $attributes['class'][] = 'form-disabled';
    }
    $output = '<div' . drupal_attributes($attributes) . '>' . "\n";

    // If #title is not set, we don't display any label or required marker.
    if (!isset($element['#title'])) {
        $element['#title_display'] = 'none';
    }
    // If #type is radio or checkbox, force #title_display to 'none'.
    if ($element['#type'] == 'radio' || $element['#type'] == 'checkbox') {
        $element['#title_display'] = 'none';
    }
    // Add controls classes.
    $prefix = $suffix = '';
    $controls['#attributes']['class'] = array('controls');
    // Controls prefix
    if (isset($element['#field_prefix'])) {
        $controls['#attributes']['class'][] = 'input-prepend';
        $prefix = '<span class="add-on">' . $element['#field_prefix'] . '</span>';
    }
    // Controls suffix
    if (isset($element['#field_suffix'])) {
        $controls['#attributes']['class'][] = 'input-append';
        $prefix = '<span class="add-on">' . $element['#field_suffix'] . '</span>';
    }

    switch ($element['#title_display']) {
        case 'before':
        case 'invisible':
            $output .= theme('form_element_label', $variables);
            $output .= '<div' . drupal_attributes($controls['#attributes']) . '>' . $prefix . $element['#children'] . $suffix . "</div>\n";
            break;

        case 'after':
            $output .= '<div' . drupal_attributes($controls['#attributes']) . '>' . $prefix . $element['#children'] . $suffix . "</div>";
            $output .= theme('form_element_label', $variables) . "\n";
            break;

        case 'none':
        case 'attribute':
            // Output no label and no required marker, only the children.
            $output .= '<div' . drupal_attributes($controls['#attributes']) . '>' . $prefix . $element['#children'] . $suffix . "</div>\n";
            break;
    }

    if (!empty($element['#description'])) {
        $output .= '<div class="help-block">' . $element['#description'] . "</div>\n";
    }

    $output .= "</div>\n";

    return $output;
}

/**
 * Theme a button control.
 *
 * @param array $variables
 *     An associative array containing the following elements:
 *       - $element: an associative array containing the properties of the element. Properties used: #attributes, #button_type, #name, #value.
 *
 * @return string
 */
function twitter_bootstrap_button($variables)
{
    $element = &$variables['element'];
    $element['#attributes']['type'] = 'submit';
    element_set_attributes($element, array('name', 'id'));
    _form_set_class($element, array('form-' . $element['#button_type']));

    $element['#attributes']['class'][] = ' btn';

    $button = '<button' . drupal_attributes($element['#attributes']) . '>';
    $button .= check_plain($element['#value']);
    $button .= "</button>\n";

    return $button;
}

/**
 * Theme an image button control.
 *
 * @param array $variables
 *     An associative array containing the following elements:
 *       - $element: an associative array containing the properties of the element. Properties used: #attributes, #button_type, #name, #src, #value.
 *
 * @return string
 */
function twitter_bootstrap_image_button($variables)
{
    $element = &$variables['element'];
    $element['#attributes']['type'] = 'submit';
    element_set_attributes($element, array('name', 'id'));
    _form_set_class($element, array('form-' . $element['#button_type']));

    $element['#attributes']['class'][] = ' btn';

    $image = $element;
    $image['#src'] = base_path() . $image['#src'];
    element_set_attributes($image, array('src', 'value'));

    $button = '<button' . drupal_attributes($element['#attributes']) . '>';
    $button .= '<img' . drupal_attributes($image['#attributes']) . '>';
    $button .= "</button>\n";

    return $button;
}

/**
 * Theme a radio button control.
 *
 * @param array $variables
 *     An associative array containing the following elements:
 *       - $element: an associative array containing the properties of the element. Properties used: #required, #return_value, #value, #attributes, #title, #description.
 *
 * @return string
 */
function twitter_bootstrap_radio($variables)
{
    $element = &$variables['element'];
    $element['#attributes']['type'] = 'radio';
    element_set_attributes($element, array('id', 'name', '#return_value' => 'value'));

    if (isset($element['#return_value']) && $element['#value'] !== FALSE && $element['#value'] == $element['#return_value']) {
        $element['#attributes']['checked'] = 'checked';
    }

    _form_set_class($element, array('form-radio'));

    $content = '<label class="radio">';
    $content .= '<input ' . drupal_attributes($element['#attributes']) . '>';
    $content .= filter_xss($element['#title']);
    $content .= '</label>';

    return $content;
}

/**
 * Theme a radio button group.
 *
 * @param array $variables
 *     An associative array containing the following elements:
 *       - $element: an associative array containing the properties of the element. Properties used: #children.
 *
 * @return string
 */
function twitter_bootstrap_radios($variables)
{
    $element = $variables['element'];
    return !empty($element['#children']) ? $element['#children'] : '';
}

/**
 * Theme a checkbox control.
 *
 * @param array $variables
 *     An associative array containing the following elements:
 *       - $element: an associative array containing the properties of the element. Properties used: #title, #value, #return_value, #description, #required, #attributes, #checked.
 *
 * @return string
 */
function twitter_bootstrap_checkbox($variables)
{
    $element = &$variables['element'];
    $element['#attributes']['type'] = 'checkbox';
    element_set_attributes($element, array('id', 'name', '#return_value' => 'value'));

    if (!empty($element['#checked'])) {
        $element['#attributes']['checked'] = 'checked';
    }

    _form_set_class($element, array('form-checkbox'));

    $content = '<label class="checkbox">';
    $content .= '<input ' . drupal_attributes($element['#attributes']) . '>';
    $content .= filter_xss($element['#title']);
    $content .= '</label>';

    return $content;
}

/**
 * Theme a checkbox group.
 *
 * @param array $variables
 *     An associative array containing the following elements:
 *       - $element: an associative array containing the properties of the element. Properties used: #children.
 *
 * @return string
 */
function twitter_bootstrap_checkboxes($variables)
{
    $element = $variables['element'];
    return !empty($element['#children']) ? $element['#children'] : '';
}

/**
 * Theme text field type URL.
 *
 * @param array $variables
 *     An associative array containing:
 *       - $element: An associative array containing the properties of the element.
 *                   Properties used: #title, #value, #description, #size, #maxlength,
 *                                    #required, #attributes, #autocomplete_path, #placeholder.
 *
 * @return string
 */
function twitter_bootstrap_urlfield($variables)
{
    $element = &$variables['element'];
    $element['#attributes']['type'] = 'url';

    if (!isset($element['#field_prefix'])) {
        $element['#field_prefix'] = '<i class="icon-globe"></i>';
    }

    return twitter_bootstrap_textfield($variables);
}

/**
 * Theme text field type search.
 *
 * @param array $variables
 *     An associative array containing:
 *       - $element: An associative array containing the properties of the element.
 *                   Properties used: #title, #value, #description, #size, #maxlength,
 *                                    #required, #attributes, #autocomplete_path, #placeholder.
 *
 * @return string
 */
function twitter_bootstrap_searchfield($variables)
{
    $element = &$variables['element'];
    $element['#attributes']['type'] = 'search';

    if (!isset($element['#field_prefix'])) {
        $element['#field_prefix'] = '<i class="icon-search"></i>';
    }

    return twitter_bootstrap_textfield($variables);
}

/**
 * Theme text field type telephone number.
 *
 * @param array $variables
 *     An associative array containing:
 *       - $element: An associative array containing the properties of the element.
 *                   Properties used: #title, #value, #description, #size, #maxlength,
 *                                    #required, #attributes, #autocomplete_path, #placeholder.
 *
 * @return string
 */
function twitter_bootstrap_telfield($variables)
{
    $element = &$variables['element'];
    $element['#attributes']['type'] = 'search';

    return twitter_bootstrap_textfield($variables);
}

/**
 * Theme text field type e-mail.
 *
 * @param array $variables
 *     An associative array containing:
 *       - $element: An associative array containing the properties of the element.
 *                   Properties used: #title, #value, #description, #size, #maxlength,
 *                                    #required, #attributes, #autocomplete_path, #placeholder.
 *
 * @return string
 */
function twitter_bootstrap_emailfield($variables)
{
    $element = &$variables['element'];
    $element['#attributes']['type'] = 'search';

    return twitter_bootstrap_textfield($variables);
}

/**
 * Theme text field type number.
 *
 * @param array $variables
 *     An associative array containing:
 *       - $element: An associative array containing the properties of the element.
 *                   Properties used: #title, #value, #description, #size, #maxlength,
 *                                    #required, #attributes, #autocomplete_path, #placeholder,
 *                                    #min, #max, #step.
 *
 * @return string
 */
function twitter_bootstrap_numberfield($variables)
{
    $element = &$variables['element'];
    $element['#attributes']['type'] = 'search';

    // Set field specific attributes.
    element_set_attributes($element, array('min', 'max', 'step'));

    return twitter_bootstrap_textfield($variables);
}

/**
 * Theme text field type range.
 *
 * @param array $variables
 *     An associative array containing:
 *       - $element: An associative array containing the properties of the element.
 *                   Properties used: #title, #value, #description, #size, #maxlength,
 *                                    #required, #attributes, #autocomplete_path, #placeholder,
 *                                    #min, #max, #step.
 *
 * @return string
 */
function twitter_bootstrap_rangefield($variables)
{
    $element = &$variables['element'];
    $element['#attributes']['type'] = 'search';

    // Set field specific attributes.
    element_set_attributes($element, array('min', 'max', 'step'));

    return twitter_bootstrap_textfield($variables);
}

/**
 * Theme text field type.
 *
 * @param array $variables
 *     An associative array containing:
 *       - $element: An associative array containing the properties of the element.
 *                   Properties used: #title, #value, #description, #size, #maxlength,
 *                                    #required, #attributes, #autocomplete_path, #placeholder.
 *
 * @return string
 */
function twitter_bootstrap_textfield($variables)
{
    $element = $variables['element'];
    if (empty($element['#attributes']['type'])) {
        $element['#attributes']['type'] = 'text';
    }

    element_set_attributes($element, array('id', 'name', 'value', 'size', 'maxlength', 'placeholder'));
    _form_set_class($element, array('form-text'));

    // Autocomplete field additions
    $extra = '';
    if ($element['#autocomplete_path'] && drupal_valid_path($element['#autocomplete_path'])) {
        drupal_add_library('system', 'drupal.autocomplete');
        $element['#attributes']['class'][] = 'form-autocomplete';

        $attributes = array();
        $attributes['type'] = 'hidden';
        $attributes['id'] = $element['#attributes']['id'] . '-autocomplete';
        $attributes['value'] = url($element['#autocomplete_path'], array('absolute' => TRUE));
        $attributes['disabled'] = 'disabled';
        $attributes['class'][] = 'autocomplete';
        $extra = '<input' . drupal_attributes($attributes) . ' />';
    }

    $output = '<input' . drupal_attributes($element['#attributes']) . ' />';

    return $output . $extra;
}