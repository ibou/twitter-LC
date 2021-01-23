<?php

namespace Twitter\Controller;

use Twitter\Http\Request;
use Twitter\Http\Response;
use Twitter\Model\TweetModelInterface;
use Twitter\Validation\RequestValidator;

class TweetController
{
    protected TweetModelInterface $model;
    protected array $requiredFields = [
        'author',
        'content',
    ];
    protected RequestValidator $requestValidator;
    
    public function __construct(TweetModelInterface $model, RequestValidator $requestValidator)
    {
        $this->model = $model;
        $this->requestValidator = $requestValidator;
    }
    
    public function saveTweet(Request $request): Response
    {
        $response = $this->requestValidator->validateFields($request, $this->requiredFields);
        
        if ($response !== null) {
            return $response;
        }
        
        $this->model->save($request->get('author'), $request->get('content'));
        
        // On retourne une réponse vide, dont le status est 302 (redirection)
        // et dont l'adresse de redirection est "/"
        return new Response('', 302, ['Location' => '/',]);
    }
    
    public function deleteTweet(Request $request): Response
    {
        $id = $request->get('id');
        
        if ($id === null) {
            return new Response("Vous devez spécifier l'identifiant du tweet à supprimer", 400);
        }
        
        $this->model->delete($id);
        
        return new Response(
            '',
            302,
            [
                'Location' => '/',
            ]
        );
    }
    
    public function displayTweet(Request $request): Response
    {
        $id = $request->get('id');
        
        $tweet = $this->model->findById($id);
        
        if (!$tweet) {
            return new Response("Aucun tweet ne possède l'identifiant $id", 404);
        }
        
        $html = sprintf(
            '
            <h1>%s</h1>
            <p>%s</p>
        ',
            $tweet->author,
            $tweet->content
        );
        
        return new Response($html);
    }
    
    public function displayAllTweets(): Response
    {
        $tweets = $this->model->findAll();
        
        $html = '';
        
        foreach ($tweets as $tweet) {
            $html .= sprintf(
                '
                <h1>%s</h1>
                <p>%s</p>
            ',
                $tweet->author,
                $tweet->content
            );
        }
        
        return new Response($html);
    }
}
