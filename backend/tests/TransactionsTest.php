<?php
use PHPUnit\Framework\TestCase;
class RestaurantTest extends TestCase {
   public function setUp(): void
   {
       require_once __DIR__ . '/../vendor/autoload.php';
       require_once __DIR__ . '/../index.php';
       Flight::halt(false);  // this is used to prevent auto-exit during test
       Flight::start();  // here we need to start the app
   }
   public function testGetAllTransactions()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/transactions';
        ob_start();
        Flight::start();
        $output = ob_get_clean();

        $this->assertEquals(200, http_response_code());
        $this->assertJson($output);
    }

    public function testGetTransactionById()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/transactions/1';
        ob_start();
        Flight::start();
        $output = ob_get_clean();

        $this->assertEquals(200, http_response_code());
        $this->assertJson($output);
        $this->assertStringContainsString('"id":1', $output);
    }

}