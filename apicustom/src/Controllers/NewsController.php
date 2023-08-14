<?php

namespace App\Controllers;
use App\Core\Attributes\Route;
use App\Core\Controller;
use App\Core\Response;

class NewsController extends Controller
{
    public function render(array $assoc_array): string
    {
        $assoc_array['module'] = 'news';
        return parent::render($assoc_array);
    }


    /**
     * @return Response
     */
    #[Route('home')]
    public function index(): Response
    {
        return new Response('Index', "indexindexindex!!!");
    }

    /**
     * @return Response
     */
    public function list() : string
    {
        return $this->render([
            'title' => 'text'
        ]);
    }

    /**
     * @return Response
     */
    #[Route("addition")]
    public function add(): Response
    {
        return new Response('Add', "addaddaddadd!!!");
    }
}