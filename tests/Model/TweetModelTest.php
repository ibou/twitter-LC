<?php

namespace Tests\Model;

use PHPUnit\Framework\TestCase;
use Twitter\Model\TweetModel;
use PDO;

class TweetModelTest extends TestCase
{
    protected PDO $pdo;
    protected TweetModel $model;

    protected function setUp(): void
    {
        // Setup : on va vider la base de données
        $this->pdo = new PDO('mysql:host=localhost;dbname=tweetters;charset=utf8', 'root', 'root', [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);

        $this->pdo->query("DELETE FROM tweet");
        $this->model = new TweetModel($this->pdo);
    }

    public function testWeCanSaveATweet()
    {
        // Etant donné un auteur et un contenu
        $author = "Ibou";
        $content = "Test de tweet";

        // Quand j'appelle mon model et que je veux sauver un tweet
        $newTweetId = $this->model->save($author, $content);

        // Alors je reçois bien un identifiant
        $this->assertNotNull($newTweetId);
        // Et le tweet correspondant à cet identifiant existe bien
        $tweet = $this->pdo->query('SELECT * FROM tweet WHERE id = ' . $newTweetId)->fetch();

        $this->assertNotFalse($tweet);
        $this->assertEquals($author, $tweet['author']);
        $this->assertEquals($content, $tweet['content']);
    }

    public function testWeCanDeleteATweet()
    {
        // Etant donné un tweet existant
        $tweetId = $this->model->save("Ibou", "Un tweet");

        // Quand je supprime à l'aide du model
        $this->model->delete($tweetId);

        // Alors le tweet n'apparait plus dans la base
        $results = $this->pdo->query("SELECT t.* FROM tweet t WHERE id = $tweetId")->rowCount();
        $this->assertEquals(0, $results);
    }


    public function testWeCanFindTweetWithId()
    {
        // Etant donné un tweet existant
        $tweetId = $this->model->save("Ibou", "Un tweet");

        // Quand je recherche le tweet avec son id
        $tweet = $this->model->findById($tweetId);

        // Alors le tweet devrait exister
        $this->assertNotNull($tweet);
        // Et contenir les mêmes informations
        $this->assertEquals("Ibou", $tweet->author);
        $this->assertEquals("Un tweet", $tweet->content);
    }


    public function testWeCantFindAnUnexistingTweet()
    {
        // Quand je recherche un tweet inexistant
        $tweet = $this->model->findById(42);

        // Alors le tweet devrait être null
        $this->assertNull($tweet);
    }

    public function testWeCanFindAllTweets()
    {
        // Etant donné un nombre aléatoire de tweets en base de données
        $count = mt_rand(3, 20);
        for ($i = 0; $i < $count; $i++) {
            $this->model->save("Author $i", "Content $i");
        }

        // Quand je demande la liste des tweets
        $tweets = $this->model->findAll();

        // Alors je devrais retrouver autant de tweets que ce qu'il y a dans la base de données
        $this->assertIsArray($tweets);
        $this->assertCount($count, $tweets);
    }
}
