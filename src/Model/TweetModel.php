<?php

namespace Twitter\Model;

class TweetModel
{
    
    protected \PDO $pdo;
    
    /**
     * TweetController constructor.
     * @param \PDO $pdo
     */
    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }
    
    
    public function save($author, $content): int
    {
        $query = $this->pdo->prepare(
            "INSERT INTO tweet SET
                content=:content,
                author=:author,
                created_at= NOW()
                "
        );
        $query->execute(
            [
                'content' => $content,
                'author' => $author,
            ]
        );
        // On retourne l'identifiant du tweet nouvellement créé
        return $this->pdo->lastInsertId();
    }
}