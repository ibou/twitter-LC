<?php


namespace Tests\Controller;


use PHPUnit\Framework\TestCase;
use Twitter\Controller\TweetController;
use Twitter\Http\Request;
use Twitter\Model\TweetModel;
use PDO;
use Twitter\Validation\RequestValidator;

class TweetControllerTest extends TestCase
{
    protected PDO $pdo;
    
    protected TweetController $controller;
    
    protected TweetModel $tweetModel;
    
    public function test_a_user_can_save_a_tweet()
    {
        $request = new Request(
            [
                'author' => 'ibou',
                'content' => 'mon premier tweet',
            ]
        );
        
        $response = $this->controller->saveTweet($request);
        
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertArrayHasKey('Location', $response->getHeaders());
        $this->assertEquals('/', $response->getHeaders()['Location']);
        
        $result = $this->pdo->query('SELECT t.* FROM tweet AS t');
        $this->assertEquals(1, $result->rowCount());
        $data = $result->fetch();
        $this->assertEquals('ibou', $data['author']);
        $this->assertStringContainsString('mon premier tweet', $data['content']);
    }
    
    public function missingFieldProvider(): \Generator
    {
        yield [
            ['author' => 'ibou',],
            'Le champs content est manquant',
        ];
        yield [
            ['content' => 'un content test',],
            'Le champs author est manquant',
        ];
        yield [
            [],
            'Les champs author, content sont manquants',
        ];
    }
    
    /**
     * @param array $postData
     * @param string $errorMessage
     * @dataProvider missingFieldProvider
     */
    public function test_it_cant_save_a_tweet_if_fields_are_missing(array $postData, string $errorMessage)
    {
        $request = new Request($postData);
        $response = $this->controller->saveTweet($request);
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals($errorMessage, $response->getContent());
    }
    
    protected function setUp(): void
    {
        $this->pdo = new \PDO(
            'mysql:host=localhost;dbname=tweetters;charset=utf8', 'root', 'root', [
                                                                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                                                                ]
        );
        $this->pdo->query('DELETE FROM tweet');
        $this->tweetModel = new TweetModel($this->pdo);
        $this->controller = new TweetController($this->tweetModel, new RequestValidator());
    }
    
}
