<?php

namespace Drupal\sathyam_prakaash\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\sathyam_prakaash\ConfigService;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Controller to display custom service template.
 */
class SPController extends ControllerBase {

  /**
   * The custom config service.
   *
   * @var \Drupal\sathyam_prakaash\ConfigService
   */

  protected $configService;

  /**
   * Constructor for the Controller.
   */
  public function __construct(ConfigService $configService) {
    $this->configService = $configService;
  }

  /**
   * Dependency injection.
   */
  public static function create(ContainerInterface $container) {
    return new static(
       $container->get('sathyam_prakaash.config_service')
     );
  }

  /**
   * Returns rendered array for the controller.
   */
  public function banner() {
    // Defining the rendering function.
    $data = $this->configService->getConfigFormName();
    $build = [
    // Using the templte we created here.
      '#theme' => 'sp_template',
      '#message' => $data,
      '#hexcode' => '#FBB117',
    ];
    // Return the render array as a Response object.
    return $build;
  }

}
