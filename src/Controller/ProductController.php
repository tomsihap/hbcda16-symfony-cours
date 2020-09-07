<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/products", name="product_index", methods={"GET"})
     */
    public function index()
    {

        $products = [
            [
                'id' => 1,
                'title' => 'Lorem',
                'description' => 'foobar',
                'price' => 150,
                'quantity' => 13,
            ], [
                'id' => 2,
                'title' => 'Lorem',
                'description' => 'foobar',
                'price' => 54,
                'quantity' => 12,
            ],
            [
                'id' => 3,
                'title' => 'Lorem',
                'description' => 'foobar',
                'price' => 23,
                'quantity' => 1,
            ],
            [
                'id' => 4,
                'title' => 'Lorem',
                'description' => 'foobar',
                'price' => 12,
                'quantity' => 9,
            ],
            [
                'id' => 5,
                'title' => 'Lorem',
                'description' => 'foobar',
                'price' => 549,
                'quantity' => 0,
            ],
        ];


        return $this->render('product/index.html.twig', [
            'products' => $products
        ]);
    }

    /**
     * @Route("/products/{id}", name="product_show", methods={"GET"})
     */
    public function show(int $id)
    {
        $product = [
            'id' => $id,
            'title' => 'Faux produit',
            'description' => 'Vraie description',
            'price' => 200,
            'quantity' => 20,
        ];

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

        // Récupérer les paramètres POST du formulaire:
        dump($request->request);

        // Récupérer un paramètre POST :
        dump($request->request->get('price'));

        dd($request);
    }
}
