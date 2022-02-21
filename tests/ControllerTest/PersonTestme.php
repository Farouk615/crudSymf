<?php

namespace App\Tests\ControllerTest;

//use http\Message\Body;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
//use App\Entity\User;


class PersonTestme extends WebTestCase
{

    public function testController()
    {
//        $json='{"email":"manolas@gmail.com","password":"kostas"}';
//        dump($json);
        $client = static::createClient();
        $client->request('POST', '/api/update/3');
        $this->assertSame(401,$client->getResponse()->getStatusCode());
    }
}