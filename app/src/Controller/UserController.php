<?php

namespace App\Controller;

use App\Repository\EventsRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    private $security;
    private $eventsRepo;
    private $user;

    public function __construct(Security $security, EventsRepository $eventsRepo)
    {
        $this->security = $security;
        $this->eventsRepo = $eventsRepo;
        $this->user = $security->getUser();
    }

    /**
     * @Route("/user", name="app_user")
     */
    public function index(): Response
    {
        $events = $this->eventsRepo->findBy([], ['name' => 'DESC']);

        return $this->render('user/index.html.twig', compact('events'));
    }
}
/**
 * list event
 * make an event
 * list vote to do
 */
