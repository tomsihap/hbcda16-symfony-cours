<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/products", name="product_index", methods={"GET"})
     */
    public function index(ProductRepository $productRepository)
    {
        return $this->render('product/index.html.twig', [
            'products' => $productRepository->findAll()
        ]);
    }

    /**
     * @Route("/products/{id}", name="product_show", methods={"GET"})
     */
    public function show(Product $product)
    {
        return $this->render('product/show.html.twig', [
            'product' => $product
        ]);
    }

    /**
     * Affichage ET traitement du formulaire
     *
     * @Route("/products/create", name="product_create", methods={"GET", "POST"}, priority=1)
     */
    public function create(Request $request)
    {

        // On prépare  l'objet qui contiendra les données du formulaire
        $product = new Product();

        // On créée un formulaire à partir de la classe Type générée par "php bin/console make:form Product"
        // De plus, on rattache l'objet $product à ce formulaire autogénéré
        $form = $this->createForm(ProductType::class, $product);

        // On indique au formulaire de prendre en compte la requête HTTP (pour récupérer les données POST si elles existent)
        $form->handleRequest($request);

        // Si mon formulaire a été envoyé (si j'ai eu des données de POST)
        if ($form->isSubmitted() && $form->isValid()) {

            // Pas besoin d'utiliser de setters sur $product car $form a automatiquement rattaché les données de
            // $request->request vers $product. On enregistre directement en BDD avec le Entity Manager
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();

            // Enfin, on redirige vers la page de l'objet créé.
            // On a accès à $product->getId() parce que l'ORM a rattaché l'ID du produit créé à l'objet $product automatiquement
            return $this->redirectToRoute("product_show", ["id" => $product->getId()]);

        }

        return $this->render('product/create.html.twig', [
            'formProduct' => $form->createView()
        ]);
    }

    /**
     * Éditer un produit
     *
     * @Route("/products/{id}/edit", name="product_edit", methods={"GET", "POST"})
     *
     * // L'URL attend un paramètre (id)
     * // Le routeur va rattacher ce paramètre au premier paramètre de la méthode de la route edit(Product $product)
     * // Comme ce paramètre est typé Product (et non pas string, int...), Symfony va comprendre que le paramètre passé (id)
     * // correspond à la clé primaire d'un objet Product en base de données.
     * // Ainsi, en faisant Product $product, on récupère automatiquement l'objet $product correspondant.
     *
     * De plus, comme le paramètre est nommé "id" dans l'URL, alors il cherchera un Product par le champ "id".
     */
    public function edit(Request $request, Product $product) {
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $manager = $this->getDoctrine()->getManager();
            $manager->flush();

            return $this->redirectToRoute("product_show", ["id" => $product->getId() ]);
        }

        return $this->render('product/edit.html.twig', [
            'editForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/products/{id}/delete", name="product_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Product $product) {

        $token = $request->request->get('token');

        if ( $this->isCsrfTokenValid('delete', $token) ) {
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($product);
            $manager->flush();
        }
        else {
            dd('Attention, vous êtes peut-être victime d\'une arnaque !');
        }

        return $this->redirectToRoute('product_index');
    }

    /**
     * Page d'affichage du formulaire (ancienne version)
     *
     * @Route("/products/{id}/updateOld", name="products_update_old", methods={"GET"})
     */
    public function updateOld(Product $product) {
        return $this->render("product/updateOld.html.twig", [
            'product' => $product
        ]);
    }

    /**
     * Traitement du formulaire (ancienne version)
     *
     * @Route("/products/{id}/updateOld", name="products_update_traitement_old", methods={"POST"})
     *
     */
    public function updateTraitementOld(Request $request, Product $product) {

        $product->setTitle( $request->request->get('title') );
        $product->setDescription( $request->request->get('description'));
        $product->setQuantity( $request->request->get('quantity'));
        $product->setPrice($request->request->get('price'));

        $manager = $this->getDoctrine()->getManager();
        $manager->flush();

        return $this->redirectToRoute('product_show', ['id' => $product->getId() ]);

    }

}
