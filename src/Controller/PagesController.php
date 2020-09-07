<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PagesController extends AbstractController {

    public function articlesIndex() : Response
    {
        return new Response("Bienvenue sur l'index des articles");
    }

    /**
     * @Route("/a-propos", name="page_a_propos", methods={"GET"})
     */
    public function about() : Response {
        return new Response("À propos de ce site");
    }

    /**
     * 1. Utilisateur saisit example.com/traitementFormulaire
     * 2. Le front controller (public/index.php) est appelé
     * 3. Le Kernel (noyau de symfony qui gère le cycle de vie de l'application) est lancé
     * 4. La requête est gérée (Request::createFromGlobals())
     * 5. La requête est envoyée au Kernel
     * 6. Kernel: écoute l'URI ("/traitementFormulaire") et l'envoie au routeur qui décide de
     *      la méthode de controller à appeler
     * 7. Le routeur remarque qu'il y a besoin d'un paramètre à cette méthode (Request $request par ex)
     * 8. Le kernel pioche dans des variables "injectables" (autowiring) s'il y a un objet Request à injecter
    */
    /**
     * @Route("/traitementFormulaire", methods={"POST", "PUT", "PATCH"})
     */
    public function handleForm(Request $request) : Response
    {
        dd($request);
    }

    /**
     * @Route("/product/{productId}",   name="product_show",
     *                                  methods={"GET"},
     *                                  requirements={"productId"="^[-a-zA-Z]+$"}
     * )
     */
    public function productShow(string $productId) : Response
    {
        return new Response("Voici le produit #" . $productId);
    }

    /**
     * @Route("{lang}/courses/{courseName}/{chapterName}", name="course_show", methods={"GET"})
     */
    public function courseShow(string $lang, string $courseName, string $chapterName) : Response
    {

        $content = "Voici le cours " . $courseName . ", chapitre " . $chapterName . " en langue " . $lang . ".";

        return new Response($content);
    }

    /**
     * @Route("/articles/{id}", name="articles_show", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function articlesShow(int $id = 1) 
    {
        return new Response('Voici l\'article ' . $id);
    }

    /**
     * @Route("/foobar")
     */
    public function foobar() : Response
    {
        return $this->redirectToRoute("articles_show");
    }

    /**
     * @Route("/", name="app_index")
     */
    public function home() : Response
    {
        $user = [
            'firstname' => 'Hermione',
            'lastname' => 'Granger',
            'email' => 'h.granger@poudlard.univ.uk'
        ];

        dump($user);


        $articles = [
            [
                'id' => 1,
                'title' => 'Titre 1',
                'content' => 'Contenu du premier article',
            ],
            [
                'title' => 'Titre 2',
                'content' => 'Contenu du second article',
            ],
        ];

        return $this->render("pages/home.html.twig", [
            "user" => $user,
            "articles" => $articles,
            "title" => "Welcome into the homepage"
        ]);
    }

    /**
     * @Route("/mediatheque", name="mediatheque_index")
     */
    public function mediathequeIndex() : Response
    {

        $listBooks = [
            'le petit prince',
            '1q84',
            '1984'
        ];


        return $this->render('pages/mediathequeIndex.html.twig', [
            'title' => "Titre de la page !",
            'books' => $listBooks
        ]);
    }

    /**
     * @Route("/messagerie", name="app_messagerie", methods={"GET"})
     */
    public function messagerie() : Response {
        return $this->render('pages/messagerie.html.twig');
    }

    /**
     * @Route("/messagerie", name="app_handle_messagerie", methods={"POST"})
     */
    public function handleMessagerie(Request $request) : Response {
        $firstname = $request->request->get('firstname');
        $lastname = $request->request->get('lastname');
        $message = $request->request->get('message');

        return $this->render('pages/messagerieConfirm.html.twig', compact('firstname', 'lastname', 'message'));

        /**
         * Identique à :
         */
        return $this->render('pages/messagerieConfirm.html.twig', [
            'firstname' => $firstname,
            'lastname'  => $lastname,
            'message'   => $message,
        ]);
    }

}