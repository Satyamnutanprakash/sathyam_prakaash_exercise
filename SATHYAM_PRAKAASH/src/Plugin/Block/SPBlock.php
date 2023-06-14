<?php

namespace Drupal\sathyam_prakaash\Plugin\Block;

use Drupal\Core\Form\FormBuilderInterface;
use Drupal\Core\Render\RendererInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Drupal\Core\Block\BlockBase;

/**
 * Provides simple block for SPBlock.
 *
 * @Block (
 * id = "sp_plugin_block",
 * admin_label = "SP Block"
 * )
 */
class SPBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The form builder service.
   *
   * @var \Drupal\Core\Form\FormBuilderInterface
   */
  protected $formBuilder;

  /**
   * The renderer service.
   *
   * @var \Drupal\Core\Render\RendererInterface
   */
  protected $renderer;

  /**
   * ExampleBlock constructor.
   *
   * @param array $configuration
   *   The block configuration.
   * @param string $plugin_id
   *   The block plugin ID.
   * @param mixed $plugin_definition
   *   The block plugin definition.
   * @param \Drupal\Core\Form\FormBuilderInterface $formBuilder
   *   The form builder service.
   * @param \Drupal\Core\Render\RendererInterface $renderer
   *   The renderer service.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, FormBuilderInterface $formBuilder, RendererInterface $renderer) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->formBuilder = $formBuilder;
    $this->renderer = $renderer;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('form_builder'),
      $container->get('renderer')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    // Create a new instance of your form.
    $form = $this->formBuilder->getForm('\Drupal\sathyam_prakaash\Form\ModifiedForm');

    // Render the form using drupal_render().
    $render_array = [
      'form' => $form,
    ];
    $output = $this->renderer->renderRoot($render_array);
    return [
      '#markup' => $output,
    ];
  }

}
