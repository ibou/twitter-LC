<?php

namespace Tests\Model;

use Jajo\JSONDB;
use PHPUnit\Framework\TestCase;
use Twitter\Model\JsonTweetModel;

class JsonTweetModelTest extends TestCase
{
    protected JSONDB $jsonDb;
    protected JsonTweetModel $model;

    public function testWeCanSaveATweet()
    {
        $this->model->save("Ibou", "Un tweet on json");

        $tweets = $this->jsonDb->select('*')
            ->from('tweets.json')
            ->get();

        $this->assertCount(1, $tweets);
    }

    public function testWeCanFindTweetWithId()
    {
        $uniqId = $this->model->save("bibou", "Un tweet on json finded");
        $tweet = $this->model->findById($uniqId);
        $this->assertNotNull($tweet);
        $this->assertSame($uniqId, $tweet->id);
        $this->assertSame("bibou", $tweet->author);
        $this->assertStringContainsStringIgnoringCase("un tweet on json", $tweet->content);
    }

    public function testWeCantFindAnUnexistingTweet()
    {
        $uniqId = uniqId();
        $tweet = $this->model->findById($uniqId);
        $this->assertNull($tweet);
    }

    public function testWeCanFindAllTweets()
    {
        $count = mt_rand(4, 9);

        for ($i = 0; $i < $count; $i++) {
            $this->model->save("author ${i}", "content ${i}");
        }
        $tweets = $this->model->findAll();

        // Alors je devrais retrouver autant de tweets que ce qu'il y a dans la base de données
        $this->assertIsArray($tweets);
        $this->assertCount($count, $tweets);
        //Je m'attends à ce qu'un tweet soit un object
        $this->assertIsObject($tweets[0]);

        //Les auteurs et contenus sont bon

        for ($i = 0; $i < $count; $i++) {
            $tweet = $tweets[$i];
            $this->assertSame("author ${i}", $tweet->author);
            $this->assertSame("content ${i}", $tweet->content);
        }
    }

    public function testWeCanDeleteATweet()
    {
        //save first
        $uniqId = $this->model->save("ibrah", "mon content ici");

        $count = mt_rand(4, 9);

        for ($i = 0; $i < $count; $i++) {
            $this->model->save("author ${i}", "content ${i}");
        }

        $this->model->delete($uniqId);
        $tweet = $this->model->findById($uniqId);
        $this->assertNull($tweet);

        $this->assertCount($count, $this->model->findAll());
    }

    protected function setUp(): void
    {
        $this->jsonDb = new JSONDB(__DIR__ . '/../../data');
        $this->jsonDb->delete()
            ->from('tweets.json')
            ->trigger();
        $this->model = new JsonTweetModel();
    }
}
