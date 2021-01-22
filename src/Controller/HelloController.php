<?php

namespace Twitter\Controller;

use Twitter\Http\Response;

class HelloController
{


    public function hello(): Response
    {
        $name = $_GET['name'] ?? "tout le monde";
        $response = new Response();

        $response->setContent("Bonjour $name");

        return $response;
    }
}
