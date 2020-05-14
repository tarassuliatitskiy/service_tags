<?php

namespace Drupal\random_quote\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\PageCache\ResponsePolicy\KillSwitch;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\random_quote\RandomQuotesInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides an Random quote block.
 *
 * @Block(
 *  id = "random_quote",
 *  admin_label = @Translation("Random quote"),
 *  category = @Translation("D8 Tasks"),
 * )
 */
class RandomQuoteBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Random quote service.
   *
   * @var \Drupal\random_quote\RandomQuotesInterface
   */
  protected $randomQuote;

  /**
   * The kill switch.
   *
   * @var \Drupal\Core\PageCache\ResponsePolicy\KillSwitch
   */
  protected $killSwitch;

  /**
   * Constructor.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param array $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\random_quote\RandomQuotesInterface $random_quote
   *   Random quote service.
   * @param \Drupal\Core\PageCache\ResponsePolicy\KillSwitch $kill_switch
   *   The kill switch.
   */
  public function __construct(array $configuration, $plugin_id, array $plugin_definition, RandomQuotesInterface $random_quote, KillSwitch $kill_switch) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->randomQuote = $random_quote;
    $this->killSwitch = $kill_switch;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('random_quote'),
      $container->get('page_cache_kill_switch')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'uppercase_quote' => FALSE,
    ] + parent::defaultConfiguration();
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form['uppercase_quote'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Uppercase quote text ?'),
      '#default_value' => isset($this->configuration['uppercase_quote']) ? $this->configuration['uppercase_quote'] : FALSE,
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['uppercase_quote'] = (bool) $form_state->getValue('uppercase_quote');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    // Deny any page caching where the block shows up.
    $this->killSwitch->trigger();

    $quote = $this->randomQuote->getQuote();
    if ($quote && $this->configuration['uppercase_quote']) {
      $quote = strtoupper($quote);
    }

    return [
      '#type' => 'html_tag',
      '#tag' => 'div',
      '#value' => $quote,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheMaxAge() {
    // Make sure block is uncacheable.
    return 0;
  }

}
