<?php
namespace App\Tests\Repository;

use App\DataFixtures\AppFixtures;
use App\Repository\CategoryRepository;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CategoryRepositoryTest extends WebTestCase {
    
    protected $dbTool;

    public function setUp(): void
    {
        parent::setUp();

        $this->dbTool = static::getContainer()->get(DatabaseToolCollection::class)->get();
    }

    public function testCount()
    {
        $this->dbTool->loadFixtures([
            AppFixtures::class
        ]);

        $categories = static::getContainer()->get(CategoryRepository::class)->count([]);
        $this->assertEquals(10, $categories);
    }
}