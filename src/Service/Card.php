<?php
namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Class Card
 */
class Card {

  /**
   * @var \Symfony\Contracts\HttpClient\HttpClientInterface
   */
  protected $client;

  /**
   * Card constructor.
   *
   * @param \Symfony\Contracts\HttpClient\HttpClientInterface $client
   */
  public function __construct(HttpClientInterface $client) {
    $this->client = $client;
  }


  /**
   * Get cards from ws
   */
  public function getCards() {

    try {
      $response = $this->client->request(
        'GET',
        '/test/cards/586f4e7f975adeb8520a4b88'
      );

      return $response->toArray();
    } catch (\Exception $e) {
      throw new \ErrorException("Error getting cards" . $e->getMessage());
    }
  }

  /**
   * Sort Cards.
   *
   * @param array $cards
   * @param array $orderByCat
   * @param array $orderByVal
   *
   * @return array
   */
  public function sort(
    array $cards,
    array $orderByCat,
    array $orderByVal
  ): array {
    $sortedByCat = $sorted = [];
    // first sort cards by categories
    foreach ($orderByCat as $category) {
      foreach ($cards as $card) {
        if ($card['category'] === $category) {
          $sortedByCat [$category][] = $card;
        }
      }
    }

    // and then sort by values
    foreach ($sortedByCat as $category => $cards) {
      foreach ($orderByVal as $val) {
        foreach ($cards as $card) {

          if ($val === $card['value']) {
            $sorted[] = $card;
          }
        }
      }
    }

    return $sorted;
  }

  /**
   * check sort on webservice
   *
   * @param string $exerciceID
   * @param array $cards
   *
   * @return int
   * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
   */
  public function post(string $exerciceID, array $cards) {
    $response = $this->client->request(
      'POST',
      '/test/' . $exerciceID,
      [
        'json' => ['cards' => $cards],
      ]
    );

    return $response->getStatusCode();
  }
}