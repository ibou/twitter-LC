<?php

namespace Twitter\Model;

use Jajo\JSONDB;
use stdClass;

class JsonTweetModel implements TweetModelInterface
{
    protected JSONDB $jsonDb;
    
    /**
     * JsonTweetModel constructor.
     */
    public function __construct()
    {
        $this->jsonDb = new JSONDB(__DIR__.'/../../data');
    }
    
    
    public function save(string $author, string $content): string
    {
        $uniqId = uniqid();
        $this->jsonDb->insert(
            'tweets.json',
            [
                'id' => $uniqId,
                'content' => $content,
                'author' => $author,
            ]
        );
        
        return $uniqId;
    }
    
    public function findById($id): ?stdClass
    {
        $tweets = $this->jsonDb->select('*')
            ->from('tweets.json')
            ->where(
                [
                    'id' => $id,
                ]
            )
            ->get();
        
        return !empty($tweets) ? (object)$tweets[0] : null;
    }
    
    public function findAll(): array
    {
        return $this->jsonDb->select('*')
            ->from('tweets.json')
            ->get();
    }
    
    public function delete($id): void
    {
        $this->jsonDb->delete()
            ->from('tweets.json')
            ->where(
                [
                    'id' => $id,
                ]
            )
            ->trigger();
    }
}
