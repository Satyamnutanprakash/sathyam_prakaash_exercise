<?php

namespace Drupal\sathyam_prakaash;

use Drupal\Core\Config\ConfigFactoryInterface;

class ConfigService {

  /**
   * The config factory service.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Constructor for the ConfigHelper service.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory service.
   */
  public function __construct(ConfigFactoryInterface $config_factory) {
    $this->configFactory = $config_factory;
  }

  /**
   * Returns the name of the custom configuration form.
   *
   * @return string
   *   The name of the custom configuration form.
   */
  public function getConfigFormName() {
    return $this->configFactory->getEditable('sp_config_form.settings')->get('firstname');
  }

}