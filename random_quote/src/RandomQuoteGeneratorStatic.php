<?php

namespace Drupal\random_quote;

/**
 * Class RandomQuoteGeneratorStatic.
 */
class RandomQuoteGeneratorStatic implements RandomQuotesInterface {

  /**
   * An indexed array with static quotes.
   *
   * @var array
   */
  protected $staticQuotes = [
    "Be yourself; everyone else is already taken.",
    "So many books, so little time.",
    "Be who you are and say what you feel, because those who mind don't matter, and those who matter don't mind.",
    "A room without books is like a body without a soul.",
    "You only live once, but if you do it right, once is enough.",
    "In three words I can sum up everything I've learned about life: it goes on.",
    "If you want to know what a man's like, take a good look at how he treats his inferiors, not his equals.",
    "No one can make you feel inferior without your consent.",
    "If you tell the truth, you don't have to remember anything.",
    "A friend is someone who knows all about you and still loves you.",
    "Always forgive your enemies; nothing annoys them so much.",
    "To live is the rarest thing in the world. Most people exist, that is all.",
    "Live as if you were to die tomorrow. Learn as if you were to live forever.",
    "Without music, life would be a mistake.",
  ];

  /**
   * {@inheritdoc}
   */
  public function getQuote() {
    // Return random quote.
    return $this->staticQuotes[array_rand($this->staticQuotes)];
  }

}
