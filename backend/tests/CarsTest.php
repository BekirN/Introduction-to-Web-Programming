<?php
use PHPUnit\Framework\TestCase;
class CarsTest extends TestCase {
    public function setUp(): void
   {
       require_once __DIR__ . '/../vendor/autoload.php';
       require_once __DIR__ . '/../index.php';
       Flight::halt(false);  // this is used to prevent auto-exit during test
       Flight::start();  // here we need to start the app
   }
   public function testGetAllCars()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/cars';
        ob_start();
        Flight::start();
        $output = ob_get_clean();

        $this->assertEquals(200, http_response_code());
        $this->assertJson($output);
    }

    public function testGetCarById()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/cars/1';
        ob_start();
        Flight::start();
        $output = ob_get_clean();

        $this->assertEquals(200, http_response_code());
        $this->assertJson($output);
        $this->assertStringContainsString('"id":1', $output);
    }
}