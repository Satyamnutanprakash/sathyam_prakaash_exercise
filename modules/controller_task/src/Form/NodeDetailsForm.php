<?php

namespace Drupal\controller_task\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\NodeInterface;
use Drupal\user\Entity\User;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


/**
 * Configure Custom Controller Task Module settings for this site.
 */
class NodeDetailsForm extends ConfigFormBase {

  protected $configFactory;

  /**
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $account;

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a new RenderBlock instance.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager service.
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The current user service.
   */
public function __construct( EntityTypeManagerInterface $entity_type_manager, AccountInterface $account) {
      $this->entityTypeManager = $entity_type_manager;
      $this->account = $account;
  }

  /**
   * {@inheritdoc}
   */
  public static function create( ContainerInterface $container ) {
    return new static (
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
    $current_user_id = $this->account->id();
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
