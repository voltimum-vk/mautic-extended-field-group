<?php

// plugins/MauticExtendedFieldGroupBundle/EventListener/ConfigSubscriber.php

namespace MauticPlugin\MauticExtendedFieldGroupBundle\EventListener;

use Mautic\ConfigBundle\Event\ConfigEvent;
use Mautic\CoreBundle\EventListener\CommonSubscriber;
use Mautic\ConfigBundle\ConfigEvents;
use Mautic\ConfigBundle\Event\ConfigBuilderEvent;

/**
 * Class ConfigSubscriber
 */
class ConfigSubscriber extends CommonSubscriber {

  /**
   * @return array
   */
  static public function getSubscribedEvents() {
    return array(
      ConfigEvents::CONFIG_ON_GENERATE => array('onConfigGenerate', 0),
      ConfigEvents::CONFIG_PRE_SAVE => array('onConfigSave', 0)
    );
  }

  /**
   * @param ConfigBuilderEvent $event
   */
  public function onConfigGenerate(ConfigBuilderEvent $event) {
    $event->addForm(
        array(
          'bundle' => 'MauticExtendedFieldGroupBundle',
          'formAlias' => 'extendedfieldgroup_config',
          'formTheme' => 'MauticExtendedFieldGroupBundle:FormTheme\Config',
          'parameters' => $event->getParametersFromConfig('MauticExtendedFieldGroupBundle')
        )
    );
  }

  /**
   * @param ConfigEvent $event
   */
  public function onConfigSave(ConfigEvent $event) {
    /** @var array $values */
    $values = $event->getConfig();
    
    // Manipulate the values
    if (!empty($values['extendedfieldgroup_config'])) {
      foreach ($values['extendedfieldgroup_config'] as $key => $val) {
        if (is_scalar($val)) {
          $values['extendedfieldgroup_config'][$key] = htmlspecialchars($val);
        } else if (is_array($val)) {
          foreach ($val as $_key => $_val) {
            if (is_scalar($_val)) {
              $values['extendedfieldgroup_config'][$key][$_key] = htmlspecialchars($_val);
            }
          }
        }
      }
    }


    // Set updated values 
    $event->setConfig($values);
  }

}
