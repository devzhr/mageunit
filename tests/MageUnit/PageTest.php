<?php
/**
 * Test of class Mage_Cms_Helper_Page
 */
class Mage_Cms_Helper_PageTest extends MageUnit_Framework_TestCase
{
    /**
     * @var Mage_Cms_Helper_Page
     */
    protected $_subject;

    /**
     * Initialize test
     */
    protected function setUp()
    {
        $this->_subject = new Mage_Cms_Helper_Page();
    }

    /**
     * Terminate test
     */
    protected function tearDown()
    {
        $this->unsetModel('core/url');
        $this->unsetModel('cms/page');
    }

    /**
     * @covers Mage_Cms_Helper_Page::getPageUrl
     */
    public function testGetPageUrl()
    {
        $identifier = 'myId';
        $pageId = 3;

        $page = new Mage_Cms_Model_Page();
        $page->setIdentifier($identifier);
        $page->setId($pageId);
        $this->setModel('cms/page', $page);

        $urlModelMock = $this->getMockBuilder('Mage_Core_Model_Url')
            ->setMethods(array('getUrl'))
            ->getMock();
        $urlModelMock->expects($this->once())
            ->method('getUrl')
            ->with(null, array('_direct' => $identifier));
        $this->setModel('core/url', $urlModelMock);

        $this->_subject->getPageUrl($pageId);
    }
}