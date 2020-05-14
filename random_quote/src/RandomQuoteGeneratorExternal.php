<?php

namespace Drupal\random_quote;

use Drupal\Component\Serialization\Json;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class RandomQuoteGeneratorExternal.
 */
class RandomQuoteGeneratorExternal implements RandomQuotesInterface {

  /**
   * Endpoint url to get random quote.
   *
   * @var string
   */
  const QUOTE_ENDPOINT_URL = 'http://quotes.stormconsultancy.co.uk/random.json';

  /**
   * The HTTP client.
   *
   * @var \GuzzleHttp\Client
   */
  protected $httpClient;

  /**
   * A logger instance.
   *
   * @var \Psr\Log\LoggerInterface
   */
  protected $logger;

  /**
   * Constructs a new RandomQuoteGeneratorExternal object.
   *
   * @param \GuzzleHttp\ClientInterface $http_client
   *   The HTTP client.
   * @param \Psr\Log\LoggerInterface $logger
   *   A logger instance.
   */
  public function __construct(ClientInterface $http_client, LoggerInterface $logger) {
    $this->httpClient = $http_client;
    $this->logger = $logger;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('http_client')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getQuote() {
    $quote = NULL;
    try {
      $response = $this->httpClient->request('GET', self::QUOTE_ENDPOINT_URL);
      $content = (string) $response->getBody();
      $json = Json::decode($content);
      $quote = $json['quote'];
    }
    catch (RequestException $e) {
      $this->logger->error(
        'The random quote endpoint %endpoint seems to be broken because of the following error: "%error".',
        ['%endpoint' => self::QUOTE_ENDPOINT_URL, '%error' => $e->getMessage()]
      );
    }

    return $quote;
  }

}
