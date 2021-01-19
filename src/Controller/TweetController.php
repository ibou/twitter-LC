<?php


namespace Twitter\Controller;


use Twitter\Http\Request;
use Twitter\Http\Response;
use Twitter\Model\TweetModel;
use Twitter\Validation\RequestValidator;

class TweetController
{
    
    protected TweetModel $model;
    protected RequestValidator $requestValidator;
    protected array $requiredFields = [
        'author',
        'content',
    ];
    
    /**
     * TweetController constructor.
     * @param TweetModel $model
     * @param RequestValidator $requestValidator
     */
    public function __construct(TweetModel $model, RequestValidator $requestValidator)
    {
        $this->model = $model;
        $this->requestValidator = $requestValidator;
    }
    
    
    public function saveTweet(Request $request): Response
    {
       
        if ($response = $this->requestValidator->validateFields($request, $this->requiredFields)) {
            return $response;
        }
        
        
        $this->model->save($request->get('author'), $request->get('content'));
        
        return new Response(
            '', 302, [
                  'Location' => '/',
              ]
        );
    }
    
    
}