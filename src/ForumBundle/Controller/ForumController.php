<?php

namespace ForumBundle\Controller;
use ForumBundle\Entity\historique;
use ForumBundle\Entity\signaler;
use ForumBundle\Form\signalerType;
use ForumBundle\Form\SujetType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Snipe\BanBuilder\CensorWords;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use ForumBundle\Entity\Sujet;
use ForumBundle\Entity\Commentaire;
use UserBundle\Entity\User;


class ForumController extends Controller
{
    public function ajoutersujetAction(Request $request)
    {
        //$user = $this->container->get('security.token_storage')->getToken()->getUser();
        $sujet = new Sujet();/*instancier un club*/
        $em = $this->getDoctrine()->getManager();
        $Form = $this->createForm(SujetType::class, $sujet);/*creation formulaire || $club bch ye5ou ml objet heka lkol*/
        $Form->handleRequest($request);/*controller le comportement de formulaire||verifier si le formulaire elle ete soumis ou nn || garder une session de formulaire */


        if ($Form->isSubmitted() && $Form->isValid())/*verifier */ {
            $sujet->setDate(new \DateTime("now"));
            $sujet->setNbreJaime(0);
            $sujet->setStrike(0);
            $user = $this->getUser();
            $sujet->setIdUser($user);
            $sujet->setEtat(0);
            //historique
            $name = $this->getUser()->getUsername();
            $historique=new historique ();
            $historique->setIdu($user);
            $historique->setDescription("User ".$name."added a subject");
            $em->persist($historique);
            $em->flush();
            /*on fait ça pour qu'on peut utiliser les fonction du entity manager l persist w flush*/
            $manager = $this->get('mgilet.notification');
            $b=date('d/m/Y');
            $a=date('d/m/Y');
            $notif = $manager->createNotification("Forum ajoute le ".$b." par le user sous le nom .$user.");
            $notif->setMessage('');
            $tt=$em->getRepository(User::class)->findAll();
            foreach ($tt as $value) {

                $manager->addNotification(array($value), $notif, true); }
            $em->persist($sujet);
            $em->flush();

            return $this->redirectToRoute('afficher_mes_sujet');

        }
        return $this->render('@Forum/Forum/ajoutersujet.html.twig', array(
            'sujetform' => $Form->createView()
        ));
    }

    public function mesForumsAction()
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $forums = $em->getRepository('ForumBundle:Sujet')->findBy([
            "idUser" => $user
        ]);

        foreach ($forums as $forum) {

            $comments = $em->getRepository('ForumBundle:Commentaire')->findBy([
                "idF" => $forum->getIdF()
            ]);


        }


        $em = $this->getDoctrine()->getManager();
        return $this->render('@Forum/Forum/mesforums.html.twig', array(
            'forums' => $forums,
            'user' => $user,
        ));
    }

    public function afficherlesforumesAction(Request $request) //afficher les sujets
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $forum = $em->getRepository("ForumBundle:Sujet")->findAll();
        $pagination=$this->get('knp_paginator');
        dump(get_class($pagination));
        $forums  = $this->get('knp_paginator')->paginate(
            $forum,
            $request->query->get('page', 1)/*le numéro de la page à afficher*/,
            2/*nbre d'éléments par page*/
        );
        $censor = new CensorWords;
        return $this->render('@Forum/Forum/afficherlesforumes.html.twig',array(
            'forums' => $forums,
            'censorF' => $censor,
        ));
    }


    public function supprimersujetAction($id, Request $request)
    {

        $user = $this->container->get('security.token_storage')->getToken()->getUser();

        $forum = $this->getDoctrine()->getRepository('ForumBundle:Sujet')->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($forum);
        $em->flush();
        $request->getSession()
            ->getFlashBag()
            ->add('success', 'Le sujet a été supprimer avec succées ...!');
        //historique
        $name = $this->getUser()->getUsername();
        $historique=new historique ();
        $historique->setIdu($user);
        $historique->setDescription("User ".$name."deleted a subject");
        $em->persist($historique);
        $em->flush();

        return $this->redirectToRoute('afficher_mes_sujet');
    }


    public function modifiersujetAction($id, Request $request)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $forum = $em->getRepository(Sujet::class)->find($id);
        $forum->setDate(new \DateTime("now"));
        $Form = $this->createForm(SujetType::class, $forum);
        $Form->handleRequest($request);
        if ($Form->isSubmitted()) {
            $forum->setIdUser($user);
            $em = $this->getDoctrine()->getManager();
            $em->persist($forum);
            $em->flush();
            return $this->redirectToRoute('afficher_mes_sujet');
        }
        return $this->render('@Forum/Forum/modifiersujet.html.twig', array(
            'modifform' => $Form->createView()
        ));
    }



    public function validersujetAction(Request $request, Sujet $forum)
    {
        $em = $this->getDoctrine()->getManager();
        $forum->setEtat(1);
        $this->getDoctrine()->getManager()->flush();
        $sujet = $em->getRepository('ForumBundle:Sujet')->findBy(array('etat' =>0));
        return $this->redirectToRoute('back_forum', array(
            'sujet' => $sujet,
        ));
    }
    public function refusersujetAction(Request $request, Sujet $forum)
    {
        $em = $this->getDoctrine()->getManager();
        $forum->setEtat(-1);
        $this->getDoctrine()->getManager()->flush();
        $sujet = $em->getRepository('ForumBundle:Sujet')->findBy(array('etat' =>0));
        return $this->redirectToRoute('back_forum', array(
            'sujet' => $sujet,
        ));
    }
    public function reloadbackAction()
    {
        $em = $this->getDoctrine()->getManager();
        $sujet = $em->getRepository('ForumBundle:Sujet')->findBy(array('etat' =>0));
        return $this->render('@Forum/Forum/sujetback.html.twig', array(
            'sujet' => $sujet,
        ));
    }



   /* public function detailsAction(Request $request,$id)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();



        $forum = $this->getDoctrine()->getRepository('ForumBundle:Sujet')->find($id);



        return $this->render('@Forum/Forum/details.html.twig',array(
            'forum' => $forum,
            'user' => $user,

        ));
    }*/

    public function detailsAction(Request $request,$id)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $forum = $this->getDoctrine()->getRepository('ForumBundle:Sujet')->find($id);
        $comments = $em->getRepository('ForumBundle:Commentaire')->findBy([
            "idF"=>$id
        ]);
        $comment = new Commentaire();
        $form = $this->createFormBuilder($comment)
            ->add('descriptionCom',TextType::class,array('attr'=>array('class'=>'form-control') ))
            ->add('save',SubmitType::class,array('label'=>'Ajouter Comment','attr'=>array('class'=>'btn oneMusic-btn mt-30') ))
            ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid()) {
            $desc=$form['descriptionCom']->getData();
            $comment->setDescriptionCom($desc);
            $comment->setDateCom(new \DateTime("now"));
            $comment->setIdUser($user);
            $comment->setIdF($forum->getIdF());
            // var_dump($comment);
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();
            $request->getSession()
                ->getFlashBag()
                ->add('success', 'Commentaire a été ajouté avec succées ...!');
            return $this->redirectToRoute('details',array('id' => $id));
        }
        $em = $this->getDoctrine()->getManager();
        $ancien=$forum->getNbreJaime();
        $forum->setNbreJaime($ancien + 0 );
        $em->persist($forum);
        $em->flush();
        $repository= $em->getRepository(commentaire::class)->nombrecommentaires($forum->getIdF());

        return $this->render('@Forum/Forum/details.html.twig',array(
            'forum' => $forum,
            'comments'=>$comments,
            'user' => $user,
            'nbcom' => $repository,
            'form'=>$form->createView()

        ));
    }


    public function amanAction(Request $request,$id)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $forum = $this->getDoctrine()->getRepository('ForumBundle:Sujet')->find($id);
        $comments = $em->getRepository('ForumBundle:Commentaire')->findBy([
            "idF"=>$id
        ]);
        $comment = new Commentaire();
        $form = $this->createFormBuilder($comment)
            ->add('descriptionCom',TextType::class,array('attr'=>array('class'=>'form-control') ))
            ->add('save',SubmitType::class,array('label'=>'Ajouter Comment','attr'=>array('class'=>'btn oneMusic-btn mt-30') ))
            ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid()) {
            $desc=$form['descriptionCom']->getData();
            $comment->setDescriptionCom($desc);
            $comment->setDateCom(new \DateTime("now"));
            $comment->setIdUser($user);
            $comment->setIdF($forum->getIdF());
            // var_dump($comment);
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();
            $request->getSession()
                ->getFlashBag()
                ->add('success', 'Commentaire a été ajouté avec succées ...!');
            return $this->redirectToRoute('details',array('id' => $id));
        }
        $em = $this->getDoctrine()->getManager();
        $ancien=$forum->getNbreJaime();
        $forum->setNbreJaime($ancien + 1 );
        $em->persist($forum);
        $em->flush();
        $repository= $em->getRepository(commentaire::class)->nombrecommentaires($forum->getIdF());

        return $this->render('@Forum/Forum/details.html.twig',array(
            'forum' => $forum,
            'comments'=>$comments,
            'user' => $user,
            'nbcom' => $repository,

            'form'=>$form->createView()
        ));
    }

    public function supprimerCAction($id,Request $request)
    {

        $user = $this->container->get('security.token_storage')->getToken()->getUser();


        $com = $this->getDoctrine()->getRepository('ForumBundle:Commentaire')->find($id);

        $em =$this->getDoctrine()->getManager();
        $em->remove($com);
        $em->flush();

        return $this->redirectToRoute('details',array('id' => $com->getIdF()));
    }

    public function modifierCAction(Request $request,$id)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();

        $com = $this->getDoctrine()->getRepository('ForumBundle:Commentaire')->find($id);
        $com->setDescriptionCom($com->getDescriptionCom());
        $forum = $this->getDoctrine()->getRepository('ForumBundle:Sujet')->find($com->getIdF());

        $form = $this->createFormBuilder($com)

            ->add('descriptionCom',TextType::class,array('attr'=>array('class'=>'form-control') ))

            ->add('save',SubmitType::class,array('label'=>'modifier Comment','attr'=>array('class'=>'btn oneMusic-btn mt-30') ))
            ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid()) {
            $desc=$form['descriptionCom']->getData();

            $com->setDescriptionCom($desc);
            $com->setDateCom($com->getDateCom());
            $com->setIdUser($user);
            $com->setIdF($com->getIdF());
            // var_dump($comment);
            $em = $this->getDoctrine()->getManager();
            $em->persist($com);
            $em->flush();

            return $this->redirectToRoute('details',array('id' => $com->getIdF()));
        }


        return $this->render('@Forum/Forum/modifierC.html.twig',array(
            'form'=>$form->createView(),
            'forum' => $forum,
            'user' => $user,

        ));
    }

    public function chartAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $forums = $em->getRepository('ForumBundle:Sujet')->findAll();

        foreach ($forums as $forum) {

            $comments = $em->getRepository('ForumBundle:Commentaire')->findBy([
                "idF"=>$forum->getIdF()
            ]);

            //  $forum->setCountComments(count($comments));

        }
        $em = $this->getDoctrine()->getManager();
        return $this->render('@Forum/Forum/chart.html.twig', array(
            'forums' => $forums,
            'user' => $user,
        ));
    }

    public function signalerAction($id,Request $request)
    {
        $user = $this->getUser();
        $signaler=new signaler();
        $em = $this->getDoctrine()->getManager();
        $sujet = $this->getDoctrine()->getRepository('ForumBundle:Sujet')->find($id);
        $Form = $this->createForm(signalerType::class, $signaler);
        $Form->handleRequest($request);
        if ($Form->isSubmitted() && $Form->isValid()){
            $signaler->setIdu($user);
            $signaler->setIdsujet($sujet);
            $ancien=$sujet->getStrike();
            $sujet->setStrike($ancien+1);
            $em->persist($signaler);
            $em->flush();
            dump($signaler);
            //historique
            $name = $this->getUser()->getUsername();
            $historique=new historique();
            $historique->setIdu($user);
            $historique->setDescription("User ".$name."liked a subject");
            $em->persist($historique);
            $em->flush();
            return $this->redirectToRoute('details',array('id' => $sujet->getIdF()));
        }
        return $this->render('@Forum/Forum/signalersujet.html.twig', array(
            'signalerform' => $Form->createView(),
        ));
    }
    public function signalerbackaction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository= $em->getRepository(sujet::class)->signaler();
        $nbsignale= $em->getRepository(sujet::class)->nbresujetsignale();

        $message = \Swift_Message::newInstance()
            ->setSubject('Sujet Signaler')
            ->setFrom('mazen.benhmida@esprit.tn')
            ->setTo('mazen.benhmida@esprit.tn')
            ->setBody('Nombre de signal a depassé le nombre maximal ');
        $this->get('mailer')->send($message);
        $this->addFlash('info','Mail sent successfully');

        return $this->render('@Forum/Forum/signalerback.html.twig', array(
            'sujetsignale' => $repository,
            'nbsignale' => $nbsignale
        ));
    }
    public function supprimersujetsignalerAction($id,Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $sujet = $this->getDoctrine()->getRepository('ForumBundle:Sujet')->find($id);
        $em->remove($sujet);
        $em->flush();
        dump($sujet);
        return $this->redirectToRoute('signalerback');
    }

    public function afficherhistoriqueAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $historique = $em->getRepository("ForumBundle:historique")->findAll();
        return $this->render('@Forum/Forum/historiqueback.html.twig', array(
            'historique' => $historique,
        ));
    }

    public function deletehistoriqueAction (Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository= $em->getRepository(historique::class)->deletehistorique();
        return $this->redirectToRoute('historique');
    }



}