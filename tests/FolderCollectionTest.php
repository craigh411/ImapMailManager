<?php
/**
 * Created by PhpStorm.
 * User: Craig
 * Date: 24/01/16
 * Time: 17:56
 */
namespace Humps\MailManager\Tests;

use Humps\MailManager\Collections\FolderCollection;
use Mockery as m;

class FolderCollectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_throws_an_error_when_a_non_Folder_Collectable_is_added_by_index()
    {
        $this->setExpectedException('InvalidArgumentException');
        $collectable = m::mock('Humps\MailManager\Collections\Contracts\Collectable');
        $folderCollection = new FolderCollection();
        $folderCollection[] = $collectable;
    }

    /**
     * @test
     */
    public function it_adds_a_folder_by_index()
    {
        $folder = m::mock('Humps\MailManager\Components\Folder');
        $folderCollection = new FolderCollection();
        $folderCollection[] = $folder;
        $this->assertEquals(1, count($folderCollection));
    }
}
