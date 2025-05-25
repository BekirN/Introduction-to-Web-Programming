<?php
use PHPUnit\Framework\TestCase;
class BrandTest extends TestCase {
    
    public function setUp(): void{
       require_once __DIR__ . '/../vendor/autoload.php';
       require_once __DIR__ . '/../index.php';
       Flight::halt(false);  // this is used to prevent auto-exit during test
       Flight::start();  // here we need to start the app
    }
    
    public function testGetAllBrands(){
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/brands';
         
        ob_start();
        Flight::start();
        $output = ob_get_clean();

        $this->assertEquals(200, http_response_code());
        $this->assertJson($output);
    }

    public function testGetBrandById()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/brands/1';

        ob_start();
        Flight::start();
        $output = ob_get_clean();

        $this->assertEquals(200, http_response_code());
        $this->assertJson($output);
        $this->assertStringContainsString('"id":1', $output);
    }   
}
