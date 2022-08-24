<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {   $user=$this->getUser();
        if ($user == null){
            return $this->render('baseFront.html.twig', [
                'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
                'connected' => $this->getUser()
            ]);
        } elseif ($user->hasRole('ROLE_ADMIN')){
            return $this->render('baseBack.html.twig', [
                'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
                'connected' => $this->getUser()
            ]);
        }else{
            return $this->render('baseFront.html.twig', [
                'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
                'connected' => $this->getUser()
            ]);
        }
    }
}
