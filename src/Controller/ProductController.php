<?php

namespace App\Controller;

use App\Entity\Product;
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
     * @Route("/products/create", name="product_create", methods={"GET"}, priority=1)
     */
    public function create()
    {
        return $this->render('product/create.html.twig');
    }

    /**
     * Traitement du formulaire
     *
     * @Route("/products", name="product_new", methods={"POST"})
     */
    public function new(Request $request) {

        dd($this->container);

        // Récupérer les paramètres POST du formulaire:
        dump($request->request);

        // Récupérer un paramètre POST :
        dump($request->request->get('price'));

        $product = new Product();
        $product->setTitle( $request->request->get('title') );
        $product->setDescription( $request->request->get('description'));
        $product->setQuantity( $request->request->get('quantity'));
        $product->setPrice($request->request->get('price'));

        $this->entityManager->persist($product);
        $this->entityManager->flush();

        dd($product);

    }
}
