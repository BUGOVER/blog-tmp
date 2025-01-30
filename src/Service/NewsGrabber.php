<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Blog;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Log\LoggerInterface;
use Random\RandomException;
use Symfony\Component\DomCrawler\Crawler;

class NewsGrabber
{
    private LoggerInterface $logger;

    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly Client $client
    )
    {
    }

    public function setLogger(LoggerInterface $logger): NewsGrabber
    {
        $this->logger = $logger;

        return $this;
    }

    /**
     * @throws GuzzleException
     */
    public function importNews(): array
    {
        try {
            $response = $this->client->get(
                'https://www.engadget.com/news/'
            );
        } catch (Exception $exception) {
            $this->logger->error($exception->getMessage());
            exit(500);
        }

        $news = [];
        $crawler = new Crawler($response->getBody()->getContents());
        $crawler->filter('h4.My\(0\) > a')->each(function (Crawler $node) use (&$news) {
            $news[] = [
                'title' => $node->text(),
                'url' => $node->attr('href'),
            ];
        });
        unset($crawler);

        foreach ($news as &$item) {
            $response = $this->client->get('https://www.engadget.com' . $item['url']);
            $crawler = new Crawler($response->getBody()->getContents());
            $crawlerBody = $crawler->filter('div.caas-body')->first();
            try {
                $item['text'] = $crawlerBody->text();
            } catch (Exception $exception) {
                $this->logger->error($exception->getMessage());
            }
        }
        unset($item);

        try {
            $this->updateBlogs($news);
        } catch (Exception $exception) {
            $this->logger->error($exception->getMessage());
        }

        return $news;
    }

    /**
     * @param array $news
     * @return void
     * @throws RandomException
     */
    private function updateBlogs(array $news): void
    {
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
    }
}
