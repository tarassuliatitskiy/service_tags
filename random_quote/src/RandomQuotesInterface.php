<?php

namespace Drupal\random_quote;

/**
 * Interface RandomQuotesInterface.
 */
interface RandomQuotesInterface {

  /**
   * Get random quote.
   *
   * @return string|null
   *   Quote string or NULL.
   */
  public function getQuote();

}
