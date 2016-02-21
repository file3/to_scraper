<?php
require("src/GetData.php");

class GetDataTest extends PHPUnit_Framework_TestCase
{
    public function testGetEmpty()
    {
        $gd = new GetData();
        $gd->load();
        $this->assertEquals(false, empty($gd->res));
    }
}
?>
