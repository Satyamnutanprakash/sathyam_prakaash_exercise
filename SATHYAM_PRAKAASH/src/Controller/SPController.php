<?php

namespace Drupal\sathyam_prakaash\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\sathyam_prakaash\ConfigService;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Controller to display custom service template.
 */
class SPController extends ControllerBase implements ContainerFactoryPluginInterface {

  /**
   * The custom config service.
   *
   * @var \Drupal\sathyam_prakaash\ConfigService
   */

  protected $configService;

  /**
   * Constructor for the Controller.
   *
   * @param array $configuration
   *   The Configuration array.
   * @param string $plugin_id
   *   The Plugin string.
   * @param mixed $plugin_definition
   *   The pulgin definition.
   * @param \Drupal\sathyam_prakaash\ConfigService $configService
   *
   *   The custom config service.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, ConfigService $configService) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->configService = $configService;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
       $configuration,
       $plugin_id,
       $plugin_definition,
       $container->get('sathyam_prakaash.config_service')
     );
  }

  /**
   * Returns rendered array for the controller.
   */
  public function banner() {
    // Defining the rendering function.
    $config_form_name = $this->configService->getConfigFormName();
    $build = [
    // Using the templte we created here.
      '#theme' => 'sp_template',
      '#config_form_name' => $config_form_name,
    ];
    // Return the render array as a Response object.
    return $build;
  }

}
