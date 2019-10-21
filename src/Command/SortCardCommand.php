<?php
namespace App\Command;

use App\Service\Card;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpClient\HttpClient;

/**
 * Class SortCardCommand
 *
 * @package App\Command
 */
class SortCardCommand extends Command {

  protected static $defaultName = 'card:sort';

  protected function configure() {
    $this
      ->setDescription('Sort cards.');

  }

  protected function execute(InputInterface $input, OutputInterface $output) {

    $client = HttpClient::create(
      [
        'base_uri' => "https://recrutement.local-trust.com/",
        'verify_peer' => FALSE,
      ]
    );

    $cardService = new Card($client);


    $card = $cardService->getCards();


    if (isset($card['exerciceId'])) {
      $exerciceId = $card['exerciceId'];
      if (isset($card['data'])) {
        $data = $card['data'];

        if (isset($data['cards']) && isset($data['categoryOrder']) && isset($data['valueOrder'])) {
          $output->writeln("<comment>Received cards: </comment>");
          $output->writeln("<info>" . json_encode($card) . "</info>");

          $cards = $data['cards'];
          $categoryOrder = $data['categoryOrder'];
          $valueOrder = $data['valueOrder'];

          $sort = $cardService->sort($cards, $categoryOrder, $valueOrder);

          $output->writeln("<comment>Cards after sort: </comment>");
          $output->writeln("<info>" . json_encode($sort) . "</info>");


          $output->writeln("<comment>Server Response: ".$cardService->post($exerciceId, $sort)."  </comment>");

        }
        else {
          $output->writeln('<error>Cards not found</error>');
        }
      }
      else {
        $output->writeln('<error>Data not found</error>');
      }
    }
    else {
      $output->writeln('<error>Exercice id not found</error>');
    }


  }
}