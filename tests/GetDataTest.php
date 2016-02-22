<?php
require("src/GetData.php");

class GetDataTest extends PHPUnit_Framework_TestCase
{
    public function testGetEmpty()
    {
        $gd = new GetData();
        $gd->load();
        $this->assertEquals(false, empty($gd->res));
        $this->assertArrayHasKey("results", $gd->res);
        $this->assertEquals(false, empty($gd->res["results"]));
        $this->assertArrayHasKey("total", $gd->res);
        $this->assertGreaterThan(0, $gd->res["total"]);
    }
}
?>
