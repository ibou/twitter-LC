<?php


namespace Twitter\Model;


interface TweetModelInterface
{
    public function save(string $author, string $content);
    
    public function delete($id);
    
    public function findById($id): ?\stdClass;
    
    public function findAll(): array;
    
}