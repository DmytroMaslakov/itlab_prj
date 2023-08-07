<?php
class NewsController{

    /**
     * @return Response
     */
    public function index():Response{
        return new Response('Index', "indexindexindex!!!");
    }

    /**
     * @return Response
     */
    public function list():Response{
        return new Response('List', "listlistlistlist!!!");
    }

    /**
     * @return Response
     */
    public function add():Response{
        return new Response('Add', "addaddaddadd!!!");
    }
}