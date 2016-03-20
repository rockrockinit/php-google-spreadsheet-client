<?php
namespace GoogleSpreadsheet\Tests\Google\Spreadsheet;

use DateTime;
use SimpleXMLElement;
use Google\Spreadsheet\Worksheet;
use Google\Spreadsheet\ServiceRequestFactory;
use Google\Spreadsheet\ListFeed;

class WorksheetTest extends TestBase
{
    public function testGetId()
    {
        $worksheet = new Worksheet(
            $this->getSimpleXMLElement("worksheet")
        );

        $this->assertEquals("https://spreadsheets.google.com/feeds/worksheets/tA3TdJ0RIVEem3xQZhG2Ceg/private/full/od8", $worksheet->getId());
    }

    public function testGetUpdated()
    {
        $worksheet = new Worksheet(
            $this->getSimpleXMLElement("worksheet")
        );

        $this->assertTrue($worksheet->getUpdated() instanceof DateTime);
        $this->assertEquals("2013-02-10 21:12:33", $worksheet->getUpdated()->format("Y-m-d H:i:s"));
    }

    public function testGetTitle()
    {
        $worksheet = new Worksheet(
            $this->getSimpleXMLElement("worksheet")
        );

        $this->assertEquals("Test", $worksheet->getTitle());
    }

    public function testGetListFeed()
    {
        $feedUrl = "https://spreadsheets.google.com/feeds/list/tA3TdJ0RIVEem3xQZhG2Ceg/od8/private/full";
        
        $mockServiceRequest = $this->getMockBuilder("Google\Spreadsheet\DefaultServiceRequest")
                ->setMethods(array("get"))
                ->disableOriginalConstructor()
                ->getMock();
        
        $mockServiceRequest
            ->expects($this->once())
            ->method("get")
            ->with($this->equalTo($feedUrl))
            ->willReturn(file_get_contents(__DIR__."/xml/list-feed.xml"));
        
        ServiceRequestFactory::setInstance($mockServiceRequest);
        
        $worksheet = new Worksheet(
            $this->getSimpleXMLElement("worksheet")
        );

        $this->assertTrue($worksheet->getListFeed() instanceof ListFeed);
    }
    
    public function testGetListFeedWithQuery()
    {
        $feedUrl = "https://spreadsheets.google.com/feeds/list/tA3TdJ0RIVEem3xQZhG2Ceg/od8/private/full?reverse=true&sq=age+%3E+45";
        
        $mockServiceRequest = $this->getMockBuilder("Google\Spreadsheet\DefaultServiceRequest")
                ->setMethods(array("get"))
                ->disableOriginalConstructor()
                ->getMock();
        
        $mockServiceRequest
            ->expects($this->once())
            ->method("get")
            ->with($this->equalTo($feedUrl))
            ->willReturn(file_get_contents(__DIR__."/xml/list-feed.xml"));
        
        ServiceRequestFactory::setInstance($mockServiceRequest);
        
        $worksheet = new Worksheet(
            $this->getSimpleXMLElement("worksheet")
        );
        
        $listFeed = $worksheet->getListFeed(array("reverse" => "true", "sq" => "age > 45"));
        $this->assertTrue($listFeed instanceof ListFeed);
    }
    
}
