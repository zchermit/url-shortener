<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Url;
use AppBundle\Utils\UrlUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {

        return $this->render('default/index.html.twig');

    }

    /**
     * @Route(path="/getShortUrl")
     *
     * @param Request $request
     *
     * @return JsonResponse
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function getShortUrl(Request $request)
    {
        $errors = [];
        $shortUrl = '';

        $requestUrl = trim($request->request->get('url'));
        if ($requestUrl) {

            $em = $this->get('doctrine')->getManager();
            /* @var $url Url */
            $url = $em->getRepository('AppBundle:Url')->findOneBy([
                'url' => $requestUrl,
            ]);

            if (!$url) {

                $url = new Url();
                $url->setUrl($requestUrl);
                $shortUrl = $this->get(UrlUtils::class)->getShortUrl($url);
                $url->setShortUrl($shortUrl);
                $em->persist($url);
                $em->flush();

            } else {
                $shortUrl = $url->getShortUrl();
            }


        } else {
            $errors[] = 'Url is empty';
        }

        if ($shortUrl) {
            $context = $this->get('router')->getContext();
            $shortUrl = sprintf('%s://%s/%s', $context->getScheme(), $context->getHost(), $shortUrl);
        }

        return new JsonResponse([
            'short_url' => $shortUrl,
            'errors' => $errors,
            'status' => count($errors) == 0,
        ]);

    }

    /**
     * @Route("/{shortUrl}")
     * @param $shortUrl
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function shortUrl($shortUrl)
    {
        $url = $this->getDoctrine()->getManager()->getRepository('AppBundle:Url')->findOneBy([
            'shortUrl' => $shortUrl,
        ]);

        if (!$url) {
            throw new NotFoundHttpException('Short url not found');
        }

        return $this->redirect($url->getUrl());

    }

}
