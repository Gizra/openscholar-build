<?php

/**
 * @file
 * template.php
 */

/**
 * The adaptive theme implements a hook_menu_tree but return a rendered ul
 * wrapped with a ul - this will cause to the menu html to be"
 *  <ul...>
 *    <ul ...>
 *      ...
 *    </ul>
 *  </ul>
 *
 * We need to implement our own hook_menu_tree to prevent a double ul tag
 * wrapping. But when we're not using nice menus, use the original adaptive
 * theme.
 */
function os_bootstrap_menu_tree(&$variables) {

  $mobile_case = '/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i';

  drupal_add_js(array('huji' => array('mobile' => preg_match($mobile_case, $_SERVER['HTTP_USER_AGENT']))), 'setting');

  if (isset($variables['os_nice_menus']) && $variables['os_nice_menus']) {
    return $variables['tree'];
  }

  return '<ul class="menu clearfix">' . $variables['tree'] . '</ul>';
}

/**
 * Implements template_preprocess_HOOK() for theme_menu_tree().
 *
 * template_preprocess_menu_tree has been removed. This replaces it and sets a
 * flag when we're using nice_menus.
 */
function os_bootstrap_preprocess_menu_tree(&$variables) {
  if (isset($variables['tree']['#theme'])) {
    $variables['os_nice_menus'] = ($variables['tree']['#theme'] == 'os_nice_menus');
  }
  else {
    $variables['os_nice_menus'] = false;
  }


  if ($variables['tree']['#region'] == 'menu_bar') {
    // Wrap menu in the menu region for mobile computability.
    $variables['tree']['#children'] = '<div class="collapse navbar-collapse os_bootstrap_menu_collapse">' . $variables['tree']['#children'] . '</div>';
  }

  $variables['tree'] = $variables['tree']['#children'];
}

/**
 * Get the timestamp of the last time a VSite was updated.
 *
 * @param $nid
 *  The node id of the VSite.
 *
 * @return string|integer
 *  The timestamp of the last changed node.
 */
function os_bootstrap_get_last_update_time($nid) {
  $query = new EntityFieldQuery();
  $result = $query
    ->entityCondition('entity_type', 'node')
    ->fieldCondition(OG_AUDIENCE_FIELD, 'target_id', $nid)
    ->propertyOrderBy('changed', 'DESC')
    ->range(0, 1)
    ->execute();
  if (empty($result['node'])) {
    return '';
  }

  $node = node_load(key($result['node']));

  return $node->changed;
}


/**
 * Implements hook_preprocess_page().
 */
function os_bootstrap_preprocess_page(&$vars) {
  global $language;
  $lang = $language->language;

  $vars['tag'] = $vars['title'] ? 'section' : 'div';

  $links = array(
    'en' => array(
      'link' => 'http://new.huji.ac.il/en',
      'reserve' => 'http://new.huji.ac.il/en/page/811',
      'term' => 'http://new.huji.ac.il/en/page/811#Liability',
    ),
    'he' => array(
      'link' => 'http://huji.ac.il',
      'reserve' => 'http://new.huji.ac.il/page/810',
      'term' => 'http://new.huji.ac.il/page/810#terms',
    ),
  );

  $vsite = vsite_get_vsite();
  $init_values = array(
    'sidebar_second_column',
    'primary_local_tasks',
    'secondary_local_tasks',
    'content_top',
    'content_first',
    'content_second',
    'content_bottom',
    'footer_top',
    'footer_first',
  );
  foreach ($init_values as $key) {
    $vars[$key] = !empty($vars[$key]) ? $vars[$key] : '';
  }

  $path = $lang == 'he' ? drupal_get_path('theme', 'os_bootstrap') . '/images/logo.png' : drupal_get_path('theme', 'os_bootstrap') . '/images/logo_ltr.png' ;
  $huji_logo_text = t('The Hebrew University of Jerusalem, Home page');
  $logo = theme('image', array(
    'path' => $path,
    'attributes' => array(
      'class' => array('img-responsive'),
      'width' => array(278),
      'height' => array(69),
    ),
    'alt' => $huji_logo_text,
  ));

  array_unshift($vars['page']['header_first'], array(
    'logo' => array(
      'content' => array('#markup' => l($logo, $links[$lang]['link'], array('html' => TRUE))),
    ),
    'open-menu' => array(
      'content' => array('#markup' => '
        <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed os_bootstrap_collapse_button" data-toggle="collapse" data-target="#os_bootstrap_menu_collapse" aria-expanded="false">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
      </div>
      '),
    ),
  ));

  $image_path = drupal_get_path('theme', 'os_bootstrap') . '/images';

  $vars['bottom_bar'] = theme('image', array('path' => $image_path . '/bottom-bar.png', 'attributes' => array('class' => array('img-responsive', 'bottom-bar'))));

  // Footer menu.
  $attributes = array(
    'class' => array('img-responsive'),
    'width' => array(198),
    'height' => array(48),
  );
  $vars['footer_home_page_link'] = l(theme('image', array(
    'path' => $path,
    'attributes' => $attributes,
    'alt' => $huji_logo_text,
  )), $links[$lang]['link'], array('html' => TRUE));

  $vars['footer_menu'] = array(
    'reserve' => l(t("All Rights Reserved Copyright © The Hebrew University of Jerusalem"), $links[$lang]['reserve']),
    'update' => t("Last Update: @time", array('@time' => format_date(os_bootstrap_get_last_update_time($vsite->id)), 'custom', 'd/m/Y')),
    'term' => l(t("Terms of Service"), $links[$lang]['term']),
  );

  $login_pages = array(
    'user',
    'private_site',
    'user/password'
  );

  $item = menu_get_item();
  if (isset($item) && !in_array($item['path'], $login_pages)) {
    $vars['login_link'] = theme('openscholar_login');
  }
}

/**
 * Render the gapnel element in a matching bootstrap format.
 *
 * @param $element
 *   gPanel element.
 * @param $grid
 *   How many bootstrap grid the element should take.
 *
 * @return string
 *   The element wrapped with bootstrap element.
 */
function os_bootstrap_gpanel_grid_render($element, $grid, $extra_class = '') {
  $class = $grid != 12 ? 'col-md-' . $grid . ' col-xs-12' : 'col-md-12';

  if ($extra_class) {
    $class .= ' ' . $extra_class;
  }
  $first = reset($element);
  return '<div class="' . $class . '">' . $first['#markup'] . '</div>';
}

/**
 * Implementing hook_menu_link().
 */
function os_bootstrap_menu_link(array $vars) {
  $element = $vars['element'];
  $sub_menu = '';

  if ($element['#below']) {
    $sub_menu = drupal_render($element['#below']);
  }

  if (!empty($element['#original_link'])) {
    if (!empty($element['#original_link']['depth'])) {
      $element['#attributes']['class'][] = 'menu-depth-' . $element['#original_link']['depth'];
    }
    if (!empty($element['#original_link']['mlid'])) {
      $element['#attributes']['class'][] = 'menu-item-' . $element['#original_link']['mlid'];
    }
  }

  $output = l($element['#title'], $element['#href'], $element['#localized_options']);
  return '<li' . drupal_attributes($element['#attributes']) . '>' . $output . $sub_menu . "</li>";
}

/**
 * Returns HTML for a link
 *
 * Only change from core is that this makes href optional for the <a> tag.
 */
function os_bootstrap_link(array $variables) {
  $href = ($variables['path'] === false) ? '' : 'href="' . check_plain(url($variables['path'], $variables['options'])) . '" ';
  return '<a ' . $href . drupal_attributes($variables['options']['attributes']) . '>' . ($variables['options']['html'] ? $variables['text'] : check_plain($variables['text'])) . '</a>';
}

/**
 * Implements theme_preprocess_html().
 */
function os_bootstrap_preprocess_html(&$variables) {
  global $language, $base_url;

  if ($language->language == 'he') {
    drupal_add_css(drupal_get_path('theme', 'os_bootstrap') . '/css/style_rtl.css');
    drupal_add_css(drupal_get_path('theme', 'os_bootstrap') . '/css/bootstrap-rtl.min.css', array('weight' => -5, 'group' => 200));
  }
  else {
    drupal_add_css(drupal_get_path('theme', 'os_bootstrap') . '/css/style.css');
  }

  ctools_include('themes', 'os');
  if ($flavors = os_theme_get_flavor()) {
    $flavor = reset($flavors);
    drupal_add_css($flavor['path'] . '/' . $flavor['styles'][$language->language]);
  }

  $variables['css_path'] = $base_url . '/' . drupal_get_path('theme', 'os_bootstrap') . '/css';
}

/**
 * Implements hook_js_alet().
 */
function os_bootstrap_js_alter(&$js) {
  $cdn = '//netdna.bootstrapcdn.com/bootstrap/' . theme_get_setting('bootstrap_cdn')  . '/js/bootstrap.min.js';
  $js[$cdn] = 'footer';
}


/**
 * Implements hook_bootstrap_search_form_wrapper.
 */
function os_bootstrap_bootstrap_search_form_wrapper($variables) {
  $output = '<div class="input-group">';
  $output .= $variables['element']['#children'];
  $output .= '<span class="input-group-btn">';
  $output .= '<button type="submit" tabindex="-1" class="btn btn-primary">' . _bootstrap_icon('search', t('Search')) . '</button>';
  $output .= '</span>';
  $output .= '</div>';
  return $output;
}

/**
 * Overrides theme_button().
 */
function os_bootstrap_button($variables) {
  $element = $variables['element'];

  // Allow button text to be appear hidden.
  // @see https://www.drupal.org/node/2327437
  $text = !empty($element['#hide_text']) ? '<span class="element-invisible test">' . $element['#value'] . '</span>' : $element['#value'];

  // Add icons before or after the value.
  // @see https://www.drupal.org/node/2219965
  if (!empty($element['#icon'])) {
    if ($element['#icon_position'] === 'before') {
      $text = $element['#icon'] . ' ' . $text;
    }
    elseif ($element['#icon_position'] === 'after') {
      $text .= ' ' . $element['#icon'];
    }
  }

  // This line break adds inherent margin between multiple buttons.
  if (in_array('element-invisible', $variables['element']['#attributes']['class'])) {
    $element['#attributes']['tabindex'] = '-1';
  }

  return '<button ' . drupal_attributes($element['#attributes']) . '>' . $text . "</button>\n";
}
