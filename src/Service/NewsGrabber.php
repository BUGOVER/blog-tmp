<?php

declare(strict_types=1);

namespace App\Service;

use App\Dbal\Type\BlogStatus;
use App\Entity\Blog;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Random\RandomException;
use Symfony\Component\DomCrawler\Crawler;

class NewsGrabber
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly EntityManagerInterface $entityManager,
    )
    {
    }

    /**
     * @throws GuzzleException
     * @throws RandomException
     */
    public function importNews()
    {
        $client = new Client();
        $request = $client->get(
            'https://www.engadget.com/news/'
        );

        $news = [];
        $crawler = new Crawler($request->getBody()->getContents());
        $crawler->filter('h4.My\(0\) > a')->each(function (Crawler $node) use (&$news) {
            $news[] = [
                'title' => $node->text(),
                'url' => $node->attr('href'),
            ];
        });
        unset($crawler);

        foreach ($news as &$item) {
            $response = $client->get('https://www.engadget.com' . $item['url']);
            $crawler = new Crawler($response->getBody()->getContents());
            $crawlerBody = $crawler->filter('div.caas-body')->first();
            try {
                $item['text'] = $crawlerBody->text();
            } catch (Exception $exception) {
            }
        }
        unset($item);

        $blogUser = $this->userRepository->find(65);

        foreach ($news as $item) {
            $blog = new Blog(user: $blogUser);
            $blog
                ->setTitle($item['title'])
                ->setDescription(mb_substr($item['title'], 0, random_int(50, 250)))
                ->setText($item['text']);
            $this->entityManager->persist($blog);
        }
        $this->entityManager->flush();

        return $news;
    }
}
