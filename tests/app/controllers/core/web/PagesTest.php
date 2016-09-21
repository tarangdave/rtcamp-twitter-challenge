<?php

class PagesTest extends PHPUnit_Framework_Testcase{


    public function testtwConfig()
    {
 
        
        require 'config.php';
        
        $this->assertNotEmpty(CONSUMER_KEY);
        $this->assertNotEmpty(CONSUMER_SECRET);
        
    }

   public function testCallbackUrl(){

        require 'config.php';
        $this->assertEquals(OAUTH_CALLBACK,'http://rt-camp.herokuapp.com/redirect.php');  
    }

}

?>