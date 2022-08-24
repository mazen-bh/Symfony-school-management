<?php

namespace NewsBundle\Controller;

use AppBundle\Entity\User;
use ClientBundle\Entity\Produit;
use NewsBundle\Entity\review;
use NewsBundle\Form\reviewType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use NewsBundle\Entity\News;
use NewsBundle\Form\NewsType;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use TechBundle\Entity\Article;
use TechBundle\Entity\Evenement;


class NewsController extends Controller
{

    public function showArticleAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $listearticles = $em->getRepository('NewsBundle:News')->findAll();

        /**
         * @var $paginator\Knp\Component\Pager\Paginator
         */
        $paginator  = $this->get('knp_paginator');

        $articles= $paginator->paginate(
            $listearticles,
            $request->query->get('page', 1)/*le numéro de la page à afficher*/,
            6/*nbre d'éléments par page*/
        );
        return $this->render('@News/News/Article_back.html.twig', array(
            'article' => $articles,
        ));
    }

    public function newArticleAction(Request $request)
    {

        $membre=$this->container->get('security.token_storage')->getToken()->getUser()->getId();

        $article = new News();

        $form = $this->createForm(NewsType::class,$article);
        $form = $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $file= $request->files->get('newsbundle_news')['imageArticle'];
            $uploads_directory=$this->getParameter('uploads_directory');

            $imageArticle=$file->getClientoriginalName();
            $em = $this->getDoctrine()->getManager();

            $file->move($uploads_directory,$imageArticle);
            $article->setImageArticle($imageArticle);
            $article->setIdUser($membre);

            $a=date('H:i:s d/m/Y');

            $article->setDateArticle($a);
            $b=0;
            $article->setNbrevue($b);
            $em->persist($article);
            $em->flush();
            return $this->redirect($this->generateUrl(
                "showArticle", ['id' => $article->getIdArticle()]
            ));
        }
        return $this->render('@News/News/Article_back_Add.html.twig',array('form' => $form->createView()));
    }

    public function deleteArticleAction($id) {
        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository(News::class)->find($id);
        $em->remove($article);
        $em->flush();
        return $this->redirectToRoute("showArticle");
    }

    public function editArticleAction($id , Request $request) {
        $article = new News();
        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository(News::class)->find($id);
        $form = $this->createForm(NewsType::class,$article);
        $form = $form->handleRequest($request);
        if ($form->isSubmitted())
        {


            $file= $request->files->get('newsbundle_news')['imageArticle'];
            $uploads_directory=$this->getParameter('uploads_directory');

            $imageArticle=$file->getClientoriginalName();


            $file->move($uploads_directory,$imageArticle);
            $article->setImageArticle($imageArticle);

            $a=date('H:i:s d/m/Y');

            $article->setDateArticle($a);
            $em->persist($article);
            $em->flush();
            return $this->redirectToRoute("showArticle");
        }
        return $this->render('@News/News/Article_back_Modifier.html.twig',array('form' => $form->createView()));


    }

    public function indexArticleAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $queryBuilder = $em->getRepository('NewsBundle:News')->createQueryBuilder('bp');


        $queryBuilder->orderBy("bp.dateArticle", 'DESC');
        if ($request->query->getAlnum('filter')){
            $queryBuilder
                ->where('bp.nomArticle LIKE :nomArticle')
                ->setParameter('nomArticle','%' . $request->query->getAlnum('filter') . '%');

        }
        $article=$queryBuilder->getQuery();

        /**
         * @var $paginator\Knp\Component\Pager\Paginator
         */
        $paginator  = $this->get('knp_paginator');

        $articles= $paginator->paginate(
            $article,
            $request->query->get('page', 1)/*le numéro de la page à afficher*/,
            150/*nbre d'éléments par page*/
        );


        return $this->render ("@News/News/indexArticle.html.twig",array('articles'=>$articles));
    }

    public function articleAction($id,Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository(News::class)->find($id);
        $art = $em->getRepository(News::class)->findAll();
        $article->setnbrevue($article->getnbrevue()+1);
        $em->persist($article);
        $em->flush();
        $ar = $em->getRepository(\UserBundle\Entity\User::class)->find($article->getIduser());

        $events= $em->getRepository(\NewsBundle\Entity\News::class)->findAll();
        $sql="select * from news order by nbrevue desc limit 3";
        $result=$em->getConnection()->prepare($sql);
        $result->execute();
        $statement = $result->fetchAll();
        dump($statement);

        $em = $this->getDoctrine()->getManager();

        $reviews = $em->getRepository('NewsBundle:review')->findBy([
            "idarticle"=>$id
        ]);

        $review=new review();
        $review->setEtat(1);
        $Form=$this->createForm(reviewType::class,$review);
        $Form->handleRequest($request);
        if ($Form->isSubmitted()&&$Form->isValid())
        {
            $em=$this->getDoctrine()->getManager();
            $user = $this->getUser();
            $review->setIduser($user);
            $review->setEtat(1);
            $review->setIdarticle($article);
            $em->persist($review);
            $em->flush();
            return $this->redirectToRoute('article',array(
                'id' => $id,

            ));
        }


        $repository= $em->getRepository(review::class)->avgrating($id);


        return $this->render("@News/News/article.html.twig",array(
            'article'=>$article,
            'ar'=>$ar,
            'aman'=>$statement,
            'art'=>$art,
            'event'=>$events,
            'review'=> $reviews,
            'reviewform'=>$Form->createView(),
            'avg'=> $repository,
            'etat' =>$review,
        ));
    }

    public function printAction($id) {
        $user=$this->container->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $news = $em->getRepository(News::class)->find($id);


        return $this->render('@News/News/print.html.twig',array('news'=>$news,'nom'=>$user));
    }

    public function trinbvueAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('NewsBundle:News')->createQueryBuilder('bp');
        $queryBuilder->orderBy("bp.nbrevue", 'DESC');
        if ($request->query->getAlnum('filter')){
            $queryBuilder
                ->where('bp.nomArticle LIKE :nomArticle')
                ->setParameter('nomArticle','%' . $request->query->getAlnum('filter') . '%');
        }
        $article=$queryBuilder->getQuery();
        /**
         * @var $paginator\Knp\Component\Pager\Paginator
         */
        $paginator  = $this->get('knp_paginator');
        $articles= $paginator->paginate(
            $article,
            $request->query->get('page', 1)/*le numéro de la page à afficher*/,
            9/*nbre d'éléments par page*/
        );
        return $this->render('@News/News/trinbvue.html.twig', array(
            'arttri'=>$articles
        ));
    }
    public function triplusrecentAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('NewsBundle:News')->createQueryBuilder('bp');
        $queryBuilder->orderBy("bp.dateArticle", 'DESC');
        if ($request->query->getAlnum('filter')){
            $queryBuilder
                ->where('bp.nomArticle LIKE :nomArticle')
                ->setParameter('nomArticle','%' . $request->query->getAlnum('filter') . '%');
        }
        $article=$queryBuilder->getQuery();
        /**
         * @var $paginator\Knp\Component\Pager\Paginator
         */
        $paginator  = $this->get('knp_paginator');
        $articles= $paginator->paginate(
            $article,
            $request->query->get('page', 1)/*le numéro de la page à afficher*/,
            9/*nbre d'éléments par page*/
        );
        return $this->render('@News/News/trinbvue.html.twig', array(
            'arttri'=>$articles
        ));
    }
    public function trimoinsrecentAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('NewsBundle:News')->createQueryBuilder('bp');
        $queryBuilder->orderBy("bp.dateArticle", 'ASC');
        if ($request->query->getAlnum('filter')){
            $queryBuilder
                ->where('bp.nomArticle LIKE :nomArticle')
                ->setParameter('nomArticle','%' . $request->query->getAlnum('filter') . '%');
        }
        $article=$queryBuilder->getQuery();
        /**
         * @var $paginator\Knp\Component\Pager\Paginator
         */
        $paginator  = $this->get('knp_paginator');
        $articles= $paginator->paginate(
            $article,
            $request->query->get('page', 1)/*le numéro de la page à afficher*/,
            9/*nbre d'éléments par page*/
        );
        return $this->render('@News/News/trinbvue.html.twig', array(
            'arttri'=>$articles
        ));
    }


    /////////////////////////////////////////////////////////////////////////////////////////////////////////////// mobile
    public function newMAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $article = new News();
        $article->setNomArticle($request->get('Nom_Article'));
        $article->setIdUser(1);
        $article->setNbrevue(1);
        $article->setContenuArticle($request->get('Contenu_Article'));
        $a=date('H:i:s d/m/Y');

        $article->setDateArticle($a);
        $article->setTitreEvent($request->get('Titre_Event'));
        $article->setImageArticle( $request->get('Image_Article'));


        $em->persist($article);
        $em->flush();

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($article);
        return new JsonResponse($formatted);
    }



    public function allAction()
    {
        $article = $this->getDoctrine()->getManager()
            ->getRepository('NewsBundle:News')
            ->findAll();

        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(2);
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });


        $normalizers = array($normalizer);

        $serializer = new Serializer($normalizers);
        $formatted = $serializer->normalize($article);
        return new JsonResponse($formatted);

    }





    public function chercherAction($titre)
    {
        $em = $this->getDoctrine()->getManager();
        $Evenement = $em->getRepository("NewsBundle:News")->findevent($titre);
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($Evenement);
        return new JsonResponse($formatted);
    }

    public function supprimerArticleAction($id)
    {   $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository("NewsBundle:News")->find($id);
        $em->remove($event);
        $em->flush();
        $serializer=new Serializer([new ObjectNormalizer()]);
        $formatted=$serializer->normalize($event);
        return new JsonResponse($formatted);
    }





    public function allarticleAction(){
        $article = new News();
        $ev=$this->getDoctrine()->getManager();
        $article= $ev->getRepository("NewsBundle:News")->findAll();


        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($article);
        return new JsonResponse($formatted);
    }

    public function oneArticleAction($id)
    {   $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository("NewsBundle:News")->find($id);

        $serializer=new Serializer([new ObjectNormalizer()]);
        $formatted=$serializer->normalize($event);
        return new JsonResponse($formatted);
    }


    public function updateArticleAction(Request $request,$id)
    {
        $em=$this->getDoctrine()->getManager();
        $article=$em->getRepository("NewsBundle:News")->find($id);
        $article->setNomArticle($request->get('titre'));
        $article->setContenuArticle($request->get('contenuArticle'));
        $article->setTitreEvent($request->get('titreEvent'));
        $article->setImageArticle($request->get('imageArticle'));
        $a=date('H:i:s d/m/Y');

        $article->setDateArticle($a);
        $article->setIdUser(1);
        $article->setNbrevue(1);
        $em->persist($article);
        $em->flush();
        return new JsonResponse("success");

    }
    public function chercherArtAction($titre)
    {
        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository("NewsBundle:News")->findBynomArticle($titre);
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($article);
        return new JsonResponse($formatted);
    }

    public function listEAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $e = $em->getRepository(News::class)->findAll();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($e);
        return new JsonResponse($formatted);
    }


    public function ChichaAction()
    {


        $produit= new News();
        $em = $this->getDoctrine()->getManager();
        $produits = $em->getRepository("NewsBundle:News")->findAll();


        $query = $em->createQuery(
            'SELECT p
    FROM NewsBundle:News p
    '
        );
        $query->setParameter("Chicha");
        $produits = $query->getResult();

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($produits);
        return new JsonResponse($formatted);

       // return $this->render('ClientBundle:Produit:Chicha.html.twig',array('produits'=>$produits));


    }


    public function StatmobileAction()
    {
        $em = $this->container->get('doctrine')->getEntityManager();
        $produits = $em->getRepository("NewsBundle:News")->findAll();
        $tab = array();
        $categories = array();
        $nbF = 0;
        $nbH = 0;
        $nbE = 0;
        $nbA = 0;
        foreach ($produits as $pd) {
            if ($pd->getTitreEvent() == "Formation") {
                $nbF = $nbF + $pd->getNbrevue();
                array_push($categories, "Formation");
            }
        }
        $serializer=new  Serializer([new ObjectNormalizer()]);
        $formatted=$serializer->normalize($nbF);
        return new JsonResponse($formatted);
    }
    public function Statmobile1Action()
    {
        $em = $this->container->get('doctrine')->getEntityManager();
        $produits = $em->getRepository("NewsBundle:News")->findAll();
        $tab = array();
        $categories = array();
        $nbF = 0;
        $nbH = 0;
        $nbE = 0;
        $nbA = 0;
        foreach ($produits as $pd) {
            if ($pd->getTitreEvent() == "Esprit int") {
                $nbF = $nbF + $pd->getNbrevue();
                array_push($categories, "Esprit int");
            }
        }
        $serializer=new  Serializer([new ObjectNormalizer()]);
        $formatted=$serializer->normalize($nbF);
        return new JsonResponse($formatted);
    }
    public function Statmobile2Action()
    {
        $em = $this->container->get('doctrine')->getEntityManager();
        $produits = $em->getRepository("NewsBundle:News")->findAll();
        $tab = array();
        $categories = array();
        $nbF = 0;
        $nbH = 0;
        $nbE = 0;
        $nbA = 0;
        foreach ($produits as $pd) {
            if ($pd->getTitreEvent() == "Esprit ext") {
                $nbF = $nbF + $pd->getNbrevue();
                array_push($categories, "Esprit ext");
            }
        }
        $serializer=new  Serializer([new ObjectNormalizer()]);
        $formatted=$serializer->normalize($nbF);
        return new JsonResponse($formatted);
    }





}
