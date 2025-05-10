<?php
declare(strict_types=1);

namespace SearchForm\Test\TestCase\Controller\Component;

use ArrayIterator;
use Cake\Controller\ComponentRegistry;
use Cake\Controller\Controller;
use Cake\Datasource\Paging\PaginatedInterface;
use Cake\Datasource\QueryInterface;
use Cake\Datasource\RepositoryInterface;
use Cake\ElasticSearch\Index;
use Cake\ElasticSearch\Query;
use Cake\Http\ServerRequest;
use Cake\ORM\Query\SelectQuery;
use Cake\ORM\Table;
use Cake\TestSuite\TestCase;
use IteratorAggregate;
use SearchForm\Controller\Component\SearchHandlerComponent;
use Traversable;

/**
 * SearchForm\Controller\Component\SearchHandlerComponent Test Case
 */
class SearchHandlerComponentTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \SearchForm\Controller\Component\SearchHandlerComponent
     */
    protected $SearchHandlerComponent;
    protected Controller $controller;

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $request = new ServerRequest(['query' => ['search' => 'query']]);
        $this->controller = new class ($request) extends Controller {
            public string $modelClass = 'Users';
            public string $name = 'Users';
            public array $viewVars = [];
            public $ElasticIndex;
            public $Users;

            public function __construct($request)
            {
                parent::__construct($request);
            }

            public function paginate(
                QueryInterface|RepositoryInterface|string|null $object = null,
                array $settings = [],
            ): PaginatedInterface {
                return new class implements PaginatedInterface, IteratorAggregate {
                    public function toArray(): array
                    {
                        return ['mocked_paginated'];
                    }

                    public function getPaginationParams(): array
                    {
                        return ['page' => 1, 'limit' => 20];
                    }

                    public function count(): int
                    {
                        return 1;
                    }

                    public function currentPage(): int
                    {
                        return 1;
                    }

                    public function perPage(): int
                    {
                        return 1;
                    }

                    public function totalCount(): ?int
                    {
                        return 1;
                    }

                    public function pageCount(): ?int
                    {
                        return 1;
                    }

                    public function hasPrevPage(): bool
                    {
                        return false;
                    }

                    public function hasNextPage(): bool
                    {
                        return false;
                    }

                    public function items(): iterable
                    {
                        return null;
                    }

                    public function pagingParam(string $name): mixed
                    {
                        return null;
                    }

                    public function pagingParams(): array
                    {
                        return [];
                    }

                    public function getIterator(): Traversable
                    {
                        return new ArrayIterator($this->toArray());
                    }
                };
            }

            public function set(array|string $name, mixed $value = null): void
            {
                if (is_array($name)) {
                    $this->viewVars = array_merge($this->viewVars, $name);
                } else {
                    $this->viewVars[$name] = $value;
                }
            }
        };

        $registry = new ComponentRegistry($this->controller);
        $this->SearchHandlerComponent = new SearchHandlerComponent($registry);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->SearchHandlerComponent);
        unset($this->controller);
        parent::tearDown();
    }

    public function testHandleWithElasticIndex(): void
    {
        $mockQuery = $this->getMockBuilder(Query::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mockIndex = $this->getMockBuilder(Index::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['find'])
            ->getMock();

        $mockIndex->expects($this->once())
            ->method('find')
            ->withAnyParameters()
            ->willReturn($mockQuery);

        $this->controller->ElasticIndex = $mockIndex;

        $this->SearchHandlerComponent->handle();

        $this->assertSame(['mocked_paginated'], $this->controller->viewVars['users']->toArray());
    }

    public function testHandleWithSearchBehavior(): void
    {
        unset($this->controller->ElasticIndex);

        $mockQuery = $this->getMockBuilder(SelectQuery::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mockTable = $this->getMockBuilder(Table::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['find', 'hasBehavior'])
            ->getMock();

        $mockTable->expects($this->once())
            ->method('hasBehavior')
            ->with('Search')
            ->willReturn(true);

        $mockTable->expects($this->once())
            ->method('find')
            ->with('search', ['search' => 'query'])
            ->willReturn($mockQuery);

        $this->controller->Users = $mockTable;

        $registry = new ComponentRegistry($this->controller);
        $this->SearchHandlerComponent = new SearchHandlerComponent($registry);
        $this->SearchHandlerComponent->initialize([]);

        $this->SearchHandlerComponent->handle();

        $this->assertSame(['mocked_paginated'], $this->controller->viewVars['users']->toArray());
    }
}
