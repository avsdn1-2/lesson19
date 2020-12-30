<?php

namespace App\Controller;

//session_start();

use App\Entity\MovieCatalog;
use App\Repository\MovieCatalogRepository;
use App\Service\CatalogServiceInterface;
use App\Service\OmdbServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Forms;
use App\Form\AddFormType;
use App\Entity\Favorite;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\HttpFoundation\Session\Session;


class SearchController extends AbstractController
{
    /**
     * @Route("/search", name="search")
     */
    public function index(CatalogServiceInterface $catalogService): Response
    {
        $result = $catalogService->search('Enemy Mine');

        return $this->render('search/index.html.twig', [
            'result' => $result,
        ]);
    }

    /**
     * @Route("/list", name="list")
     */
    public function list(CatalogServiceInterface $catalogService): Response
    {
        $result = $catalogService->list()['list'];
        $favorites_obj = $catalogService->list()['favorites'];

        /*
        $favorites = [];
        foreach ($favorites_obj as $favorit)
        {
            $favorites[] = $favorit->getFilmId();
        }
        */

        $session = $this->get('session');
        $res = $session->get('res');
        //var_dump($res);;

        return $this->render('search/list.html.twig', [
            'result' => $result,
            'favorites' => $this->toArray($favorites_obj),
            'res' => $res,
        ]);
    }

    /**
     * @Route("/favorites", name="favorites")
     */
    public function favorites(CatalogServiceInterface $catalogService): Response
    {
        $result = $catalogService->list()['list'];
        $favorites_obj = $catalogService->list()['favorites'];
        $favorites = $this->toArray($favorites_obj);
        //очищаем список от нефаворитов
        foreach ($result as $key => $value)
        {
            if (!in_array($value->getId(),$favorites))
            {
                unset($result[$key]);
            }
        }


        $session = $this->get('session');
        $res = $session->get('res');
        //var_dump($res);;

        return $this->render('search/list.html.twig', [
            'result' => $result,
            'favorites' => $this->toArray($favorites_obj),
            'res' => 1,
        ]);
    }

    /**
     * @Route("/addFilm", name="addFilm")
     */
    public function addFilm(CatalogServiceInterface $catalogService): Response
    {
        $form = $this->createForm(AddFormType::class);

        if (isset($_POST['add_form']))
        {
            $add_form = $_POST['add_form'];
            $title = $add_form['title'];
            $res = $catalogService->add($title);

            //$session = $this->getRequest()->getSession();
            $session = $this->get('session');

            // store an attribute for reuse during a later user request
            $session->set('res',$res);
            //dd($session);

            return $this->redirectToRoute('list');
            exit();
        }

        return $this->render('search/add.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /*
    public function add(MovieCatalogRepository $movieCatalogRepository, OmdbServiceInterface $omdbService): Response
    {
        $form = $this->createForm(AddFormType::class);

        if (isset($_POST['add_form']))
        {
            $add_form = $_POST['add_form'];
            $title = $add_form['title'];
            $movie = $omdbService->findByTitle($title);
            $movieCatalogRepository->save($movie);

            return $this->redirect('list');
        }


        return $this->render('search/add.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    */

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete($id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $film = $entityManager->getRepository(MovieCatalog::class)->findOneBy(['id'=>$id]);

        if (!$film) {
            return $this->redirectToRoute('list');
            exit();
        } else {
            $entityManager->remove($film);
            $entityManager->flush();

            return $this->redirectToRoute('list');
            exit();
        }
    }

    /**
     * @Route("/addFavorit/{id}", name="addFavorit")
     */
    public function addFavorit($id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $favorite = new Favorite();
        $favorite->setFilmId($id);

        $entityManager->persist($favorite);

        $entityManager->flush();

        return $this->redirectToRoute('list');
        exit();
    }

    /**
     * @Route("/removeFavorit/{id}", name="removeFavorit")
     */
    public function removeFavorit($id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $favorite = $entityManager->getRepository(Favorite::class)->findOneBy(['film_id'=>$id]);

        if (!$favorite) {
            return $this->redirectToRoute('list');
            exit();
        } else {
            $entityManager->remove($favorite);
            $entityManager->flush();

            return $this->redirectToRoute('list');
            exit();
        }
    }

    protected function toArray($favorites_obj): ?array
    {
        $favorites = [];
        foreach ($favorites_obj as $favorit)
        {
            $favorites[] = $favorit->getFilmId();
        }
        return  $favorites;
    }
}
