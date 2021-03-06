<?php
/**
 * @file os_search_solr_search_box.inc
 *
 * Improved search box.
 */
class os_search_solr_search_box extends os_boxes_default {

  /**
   * Create a new box.
   */
  protected function __construct() {
    parent::__construct();
  }

  /**
   * Implements of boxes_content::options_defaults().
   */
  public function options_defaults() {
    return parent::options_defaults() + array(
      'bundle' => array(),
      'terms' => array(),
      'vocab' => array(),
      'sort_property' => '',
      'sort_order' => '',
    );
  }

  /**
   * Implements of boxes_content::options_form().
   */
  public function options_form(&$form_state) {
    $form = parent::options_form($form_state);
    $this->options = ($this->options) ? array_merge($this->options_defaults(), $this->options) : $this->options_defaults();

    $bundle = $this->options['bundle'];
    $bundles = os_get_bundles(array(OS_PUBLIC_APP, OS_PRIVATE_APP));

    unset($bundles['feed'], $bundles['link']);

    unset($bundles['slideshow_slide'], $bundles['feed_importer']);

    $form['bundle'] = array(
      '#type' => 'select',
      '#description' => t('Which bundle would you like to use.'),
      '#title' => t('Content type'),
      '#options' => array(NULL => t('All')) + $bundles,
      '#default_value' => !empty($bundle) ? $bundle : '-none-',
      '#ajax' => array(
        'callback' => 'os_search_solr_vocabularies_switch',
        'wrapper' => 'vocab',
      ),
    );

    ctools_include('taxonomy', 'os');

    $form['vocab'] = array(
      '#type' => 'select',
      '#title' => t('Filter by: vocab'),
      '#options' => array('---' => t('----')),
      '#prefix' => '<div id="vocab">',
      '#suffix' => '</div>',
      '#ajax' => array(
        'callback' => 'os_search_solr_terms_switch',
        'wrapper' => 'terms',
      ),
      '#default_value' => $this->options['vocab'],
    );

    // In case we have a selected content type. Then filter vocabularies by it.
    // Default vocabularies are all of the m without filtering.
    $vids = array_keys(os_get_vocabularies());

    $selected_bundle = empty($form_state['values']['bundle']) ? $bundle : $form_state['values']['bundle'];
    if ($selected_bundle && $selected_bundle != '-none-') {
      $vids = array_keys(os_get_vocabularies_by_bundle('node', $selected_bundle));
    }

    $vocabularies = taxonomy_vocabulary_load_multiple($vids);

    foreach ($vocabularies as $vocabulary) {

      if (!taxonomy_get_tree($vocabulary->vid, 0, NULL, TRUE)) {
        continue;
      }

      $form['vocab']['#options'][$vocabulary->vid] = $vocabulary->name;
    }
    asort($form['vocab']['#options']);

    $form['terms'] = array(
      '#type' => 'markup',
      '#tree' => TRUE,
      '#prefix' => '<div id="terms">',
      '#suffix' => '</div>',
    );

    $vocab = !empty($form_state['values']['vocab']) ? $form_state['values']['vocab'] : $this->options['vocab'];
    if (!empty($vocab)) {
      $terms = taxonomy_get_tree($vocab, 0, NULL, TRUE);

      $taxonomies = array();
      foreach ($terms as $term) {
        $taxonomies[$term->tid] = $term->name;
      }

      $form['terms']['terms'] = array(
        '#type' => 'checkboxes',
        '#title' => t('Filter by: terms'),
        '#options' => $taxonomies,
        '#default_value' => $this->options['terms']['terms'],
      );
    }

    $form['sort_property'] = array(
      '#type' => 'select',
      '#title' => t('Sort by'),
      '#options' => array(
        'relevancy' => t('Relevancy (default)'),
        'sort_label' => t('Title'),
        'bundle' => t('Type'),
        'ds_created' => t('Date'),
      ),
      '#default_value' => $this->options['sort_property'],
    );

    $form['sort_order'] = array(
      '#type' => 'select',
      '#title' => t('Sort in'),
      '#options' => array(
        'asc' => t('Ascending'),
        'desc' => t('Descending'),
      ),
      '#default_value' => $this->options['sort_order'],
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function render() {
    $return = parent::render();

    if (module_exists('vsite') && !vsite_get_vsite()) {
      return $return;
    }

    $form_state['box_settings'] = $this->options;
    $form = drupal_build_form('search_block_form', $form_state);
    $form['#box_settings'] = $this->options;

    $return['content'] = render($form);

    return $return;
  }

}
