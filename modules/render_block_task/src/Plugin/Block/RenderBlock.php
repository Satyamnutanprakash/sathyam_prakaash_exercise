<?php

namespace Drupal\render_block_task\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a custom block that renders a selected node's label.
 *
 * @Block(
 *   id = "render_block_task",
 *   admin_label = @Translation("Render Block Task: Render Block"),
 * )
 */
class RenderBlock extends BlockBase {

  /**
   * CustomNodeInfoController constructor.
   *
   * @param array $configuration
   *   A configuration array containing information about the block.
   * @param string $plugin_id
   *   The plugin ID for the block.
   * @param mixed $plugin_definition
   *   The plugin definition for the block.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager service.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entity_type_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * The entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Dependency Injection.
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'node_id' => NULL,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);
    $config = $this->getConfiguration();

    $form['node_id'] = [
      '#type' => 'entity_autocomplete',
      '#title' => $this->t('Select a Node'),
      '#target_type' => 'node',
      '#required' => TRUE,
    ];

    if (!empty(($config['node_id']))) {
      $form['node_id']['#default_value'] = $this->entityTypeManager->getStorage('node')->load($config['node_id']);
    }
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    parent::blockSubmit($form, $form_state);
    $node = $form_state->getValue('node_id');

    $this->configuration['node_id'] = $node ? $node->id() : NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $node_id = $this->configuration['node_id'];
    $build = [];

    if ($node_id) {
      $node = $this->entityTypeManager->getStorage('node')->load($node_id);

      if ($node) {
        $build = [
          '#markup' => $node->label(),
        ];
      }
    }

    return $build;
  }

}
