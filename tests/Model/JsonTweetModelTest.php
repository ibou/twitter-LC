<?php

namespace Tests\Model;

use Jajo\JSONDB;
use PHPUnit\Framework\TestCase;
use Twitter\Model\JsonTweetModel;

class JsonTweetModelTest extends TestCase
{
    protected JSONDB $jsonDb;
    protected JsonTweetModel $model;
    protected function setUp(): void
    {
        $this->jsonDb = new JSONDB(__DIR__ . '/../../data');
        $this->jsonDb->delete()
            ->from('tweets.json')
            ->trigger();

        $this->model = new JsonTweetModel();
    }

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
    public function testWeCantFindAnUnexistingTweet(){
        $uniqId = uniqId();
        $tweet = $this->model->findById($uniqId);
        $this->assertNull($tweet);
    }
}
