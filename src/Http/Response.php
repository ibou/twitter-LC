<?php

namespace Twitter\Http;

class Response
{
    protected string $content = '';
    protected int $statusCode = 200;
    protected array $headers = [];
    
    /**
     * Response constructor.
     * @param string $content
     * @param int $statusCode
     * @param array|string[] $headers
     */
    public function __construct(
        string $content = '',
        int $statusCode = 200,
        array $headers = ['Content-type' => 'text/html']
    ) {
        $this->content = $content;
        $this->statusCode = $statusCode;
        $this->headers = $headers;
    }
    
    
    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }
    
    /**
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }
    
    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }
    
    /**
     * @param array $headers
     */
    public function setHeaders(array $headers): void
    {
        $this->headers = $headers;
    }
    
    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
    
    /**
     * @param int $statusCode
     */
    public function setStatusCode(int $statusCode): void
    {
        $this->statusCode = $statusCode;
    }
    
    
    public function send(): void
    {
        /**
         * [
         *  'Content-type' => 'text/html',
         *      'lang' =>'fr-FR'
         * ]
         */
        foreach ($this->headers as $key => $value) {
            header("$key: $value");
        }
        //le Code
        http_response_code($this->statusCode);
        
        //Le contenu
        
        echo $this->content;
    }
    
}