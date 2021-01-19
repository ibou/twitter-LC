<?php


use Twitter\Model\TweetModel;

class TweetModelTest extends \PHPUnit\Framework\TestCase
{
    public function test_it_can_save_a_test()
    {
        $pdo = new \PDO(
            'mysql:host=localhost;dbname=tweetters;charset=utf8', 'root', 'root', [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        ]
        );
        $pdo->query('DELETE FROM tweet');
        
        // Etant donné un auteur et un contenu
        $author = "Ibrah";
        $content = "Test de tweet";
        
        // Quand j'appelle mon model et que je veux sauver un tweet
        $model = new TweetModel($pdo);
        $newTweetId = $model->save($author, $content);
        
        // Alors je reçois bien un identifiant
        $this->assertNotNull($newTweetId);
        // Et le tweet correspondant à cet identifiant existe bien
        $tweet = $pdo->query('SELECT * FROM tweet WHERE id = '.$newTweetId)->fetch();
        
        $this->assertNotFalse($tweet);
        $this->assertEquals($author, $tweet['author']);
        $this->assertEquals($content, $tweet['content']);
    }
}