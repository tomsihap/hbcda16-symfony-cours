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
     * Affichage du formulaire
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

}
