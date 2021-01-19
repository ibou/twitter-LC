<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Twitter\Controller\HelloController;

class IndexTest extends TestCase
{
    protected HelloController $controller;
    
    public function test_homepage_says_hello_one()
    {
        $_GET['name'] = 'ibou';
        $response = $this->controller->hello();
        $this->assertSame("Bonjour ibou", $response->getContent());
        $this->assertEquals(200, $response->getStatusCode());
        $contentHeader = $response->getHeaders()['Content-type'] ?? null;
        $this->assertEquals("text/html", $contentHeader);
    }
    
    public function test_it_work_even_if_no_name_in_GET()
    {
        $_GET = [];
        $response = $this->controller->hello();
        $this->assertSame("Bonjour tout le monde", $response->getContent());
    }
    
    protected function setUp(): void
    {
        $this->controller = new HelloController();
    }
    
    
}
