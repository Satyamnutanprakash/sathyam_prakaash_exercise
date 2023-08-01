<?php

namespace Drupal\render_block_task\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Entity\EntityDisplayRepositoryInterface;
use Drupal\Core\Entity\EntityRepositoryInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a custom block that renders a selected node's label.
 *
 * @Block(
 *   id = "render_block_task",
 *   admin_label = @Translation("Render Block Task: Render Block"),
 * )
 */
class RenderBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The entity repository service.
   *
   * @var \Drupal\Core\Entity\EntityRepositoryInterface
   */
  protected $entityRepository;

  /**
   * The entity display repository.
   *
   * @var \Drupal\Core\Entity\EntityDisplayRepositoryInterface
   */
  protected $entityDisplayRepository;

  /**
   * Constructs a new RenderBlock instance.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager service.
   * @param \Drupal\Core\Entity\EntityRepositoryInterface $entity_repository
   *   The entity repository.
   * @param \Drupal\Core\Entity\EntityDisplayRepositoryInterface $entity_display_repository
   *   The entity display repository.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entity_type_manager, EntityRepositoryInterface $entity_repository, EntityDisplayRepositoryInterface $entity_display_repository) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityTypeManager = $entity_type_manager;
    $this->entityRepository = $entity_repository;
    $this->entityDisplayRepository = $entity_display_repository;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager'),
      $container->get('entity.repository'),
      $container->get('entity_display.repository')
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

    // Change this to the desired entity type ID.
    $entity_type_id = 'node';

    // Get available view modes for the entity type.
    $view_modes = $this->entityDisplayRepository->getViewModes($entity_type_id);

    $options = [];
    foreach ($view_modes as $view_mode => $info) {
      $options[$view_mode] = $info['label'];
    }

    $form['view_mode'] = [
      '#type' => 'radios',
      '#title' => $this->t('Select View Mode'),
      '#options' => $options,
      '#default_value' => $config['view_mode'] ?? key($options),
      '#description' => $this->t('Select the display view mode for Block'),
      // '#multiple' => FALSE,
    ];

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
    $node_id = $form_state->getValue('node_id');
    // Loading the node entity using the provided node ID.
    if (!empty($node_id)) {
      $this->configuration['node_id'] = $node_id;
    }
    else {
      $this->configuration['node_id'] = NULL;
    }

    $values = $form_state->getValues();
    $this->configuration['view_mode'] = $values['view_mode'];
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    // $config = $this->getConfiguration();
    // $node_id = $this->configuration['node_id'];
    // $build = [];
    // $node = \Drupal::routeMatch()->getParameter('node');
    // if ($node instanceof \Drupal\node\NodeInterface) {
    //   $view_builder = \Drupal::entityTypeManager()->getViewBuilder('node');
    //   $build['content'] = $view_builder->view($node, $config['view_mode']);
    // }
    // If ($node_id) {
    //   $node = $this->entityTypeManager->getStorage('node')->load($node_id);
    // if ($node) {
    //     $build = [
    //       '#markup' => $node->label(),
    //     ];
    //   }
    // }
    // Return $build;
    $config = $this->getConfiguration();
    $node_id = $this->configuration['node_id'];
    $build = [];

    if ($node_id) {
      $node = $this->entityTypeManager->getStorage('node')->load($node_id);

      if ($node) {
        $view_builder = $this->entityTypeManager->getViewBuilder('node');
        $build['content'] = $view_builder->view($node, $config['view_mode']);
      }
    }

    return $build;
  }

}
