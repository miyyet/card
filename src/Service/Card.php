<?php
namespace App\Service;

use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Class Card
 */
class Card {

  protected $client;

  public function __construct(HttpClientInterface $client) {
    $this->client = $client;
  }


  /**
   * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
   * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
   * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
   * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
   * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
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