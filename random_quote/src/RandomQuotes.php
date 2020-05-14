<?php

namespace Drupal\random_quote;

/**
 * Class RandomQuotes.
 */
class RandomQuotes implements RandomQuotesInterface {

  /**
   * An unsorted array of arrays of active quote generators.
   *
   * An associative array. The keys are integers that indicate priority. Values
   * are arrays of RandomQuotesInterface objects.
   *
   * @var \Drupal\random_quote\RandomQuotesInterface[][]
   */
  protected $generators = [];

  /**
   * Collects a quote generator.
   *
   * @param \Drupal\random_quote\RandomQuotesInterface $generator
   *   The quote generator interface.
   * @param int $priority
   *   (optional) The priority of the generator being added.
   *
   * @return $this
   */
  public function addGenerator(RandomQuotesInterface $generator, int $priority = 0) {
    $this->generators[$priority][] = $generator;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getQuote() {
    $quote = NULL;
    if ($sorted_generators = $this->sortGenerators()) {
      // Pick up the generator with the highest priority.
      $generator = array_pop($sorted_generators);
      // Get a quote from the generator.
      $quote = $generator->getQuote();
    }
    return $quote;
  }

  /**
   * Sorts generators according to priority.
   *
   * @return \Drupal\random_quote\RandomQuotesInterface[]
   *   A sorted array of quote generator objects.
   */
  protected function sortGenerators() {
    $sorted = [];
    $unsorted = $this->generators;
    ksort($unsorted);
    foreach ($unsorted as $generators) {
      $sorted = array_merge($sorted, $generators);
    }
    return $sorted;
  }

}
