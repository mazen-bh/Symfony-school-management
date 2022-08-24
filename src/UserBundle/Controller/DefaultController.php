<?php

namespace UserBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Entity\User;

class DefaultController extends Controller
{
    public function allAction()
    {
        $tasks = $this->getDoctrine()->getManager()
            ->getRepository('UserBundle:User')
            ->findAll();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($tasks);
        return new JsonResponse($formatted);
    }
    public function findAction($email){
        $tasks = $this->getDoctrine()->getManager()
            ->getRepository('UserBundle:User')
            ->findOneBy(array('email'=>$email));
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($tasks);
        return new JsonResponse($formatted);
    }
    public function newAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $user = new User();
        $user -> setUsername($request->get('username'));
        $user -> setEmail($request->get('email'));
        $user -> setPassword($request->get('password'));
        $user -> setRoles(array($request->get('role')));
        $user -> setNom($request->get('nom'));
        $user -> setPrenom($request->get('prenom'));
        $em->persist($user);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($user);
        return new JsonResponse($formatted);
    }
    public function editAction(Request $request,$id){
        $em = $this->getDoctrine()->getManager();
        $user=$em->getRepository("UserBundle:User")->find($id);
        $user -> setUsername($request->get('nom'));
        $user -> setEmail($request->get('email'));
        $user -> setPassword($request->get('password'));
        $user -> setNom($request->get('nom'));
        $user -> setPrenom($request->get('prenom'));
        $em->persist($user);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($user);
        return new JsonResponse($formatted);
    }

}
