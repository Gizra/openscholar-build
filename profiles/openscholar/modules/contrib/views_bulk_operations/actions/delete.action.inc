<?php

/**
 * @file
 * Implements a generic entity delete action. Uses Entity API if available.
 */

function views_bulk_operations_delete_action_info() {
  return array(
    'views_bulk_operations_delete_item' => array(
      'type' => 'entity',
      'label' => t('Delete item'),
      'configurable' => FALSE,
      'behavior' => array('deletes_property'),
    ),
  );
}

function views_bulk_operations_delete_item($entity, $context) {
  $info = entity_get_info($context['entity_type']);
  $entity_id = $entity->{$info['entity keys']['id']};

  entity_delete($context['entity_type'], $entity_id);
}
