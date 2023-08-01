<?php

namespace Drupal\controller_task\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\node\NodeInterface;
use Drupal\user\Entity\User;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;


/**
 * Configure Custom Controller Task Module settings for this site.
 */
class NodeDetailsForm extends ConfigFormBase implements ContainerFactoryPluginInterface {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $currentUser;

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
   * @param \Drupal\Core\Session\AccountInterface $current_user
   *   The current user service.
   */
public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entity_type_manager, AccountInterface $current_user) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityTypeManager = $entity_type_manager;
    $this->currentUser = $current_user;
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
      $container->get('current_user')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'controller_task_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['controller_task.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, NodeInterface $node = NULL) {
    if ($node === NULL) {
      return [];
    }

    // Get the default title from the node.
    $default_title = $node->label();
    $current_user_id = $this->currentUser->id();
    $current_user = $this->entityTypeManager->getStorage('user')->load($current_user_id);

    $form['title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Node Title'),
      '#default_value' => $default_title,
      '#size' => 60,
      '#maxlength' => 255,
      '#required' => TRUE,
    ];
    $form['user'] = [
      '#type' => 'entity_autocomplete',
      '#title' => $this->t('Assigned User'),
      '#target_type' => 'user',
      '#default_value' => $current_user,
      '#selection_settings' => ['include_anonymous' => FALSE],
      '#maxlength' => 60,
      '#required' => TRUE,
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $node = $form_state->getFormObject()->getEntity();

    $title = $form_state->getValue('title');
    $assigned_user_id = $form_state->getValue('user');

    $node->setTitle($title);

    $assigned_user = User::load($assigned_user_id);
    if ($assigned_user) {
      $node->set('field_assigned_user', $assigned_user);
    }

    $node->save();

    $form_state->setRedirect('controller_task.settings', ['node' => $node->id()]);
  }
}
