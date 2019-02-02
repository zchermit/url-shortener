<?php

namespace AppBundle\Utils;


use AppBundle\Entity\Url;
use AppBundle\Repository\UrlRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManager;

class UrlUtils
{
    protected $sc;
    protected $em;
    protected $repository;

    /**
     * UrlUtils constructor.
     *
     * @param EntityManager $em
     * @param ContainerInterface $sc
     */
    public function __construct(EntityManager $em, ContainerInterface $sc)
    {
        $this->sc = $sc;
        $this->em = $em;
        $this->repository = $em->getRepository('AppBundle:Url');
    }

    /**
     * @param Url $url
     *
     * @return bool|string
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function getShortUrl(Url $url)
    {
        $shortUrl = $url->getShortUrl();

        if (!$shortUrl) {
            $shortUrlExists = true;
            // Try to find uniq short url
            while ($shortUrlExists) {
                $shortUrl = $this->generateShortUrl($url);
                $shortUrlExists = $this->repository->findOneBy([
                    'shortUrl' => $shortUrl,
                ]);
            }
            $url->setShortUrl($shortUrl);
            $this->em->flush();
        }

        return $shortUrl;
    }

    /**
     * @param Url $url
     *
     * @return bool|string
     */
    public function generateShortUrl(Url $url)
    {
        return substr(md5($url->getUrl().time()), -6);
    }

}
