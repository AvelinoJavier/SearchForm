<?php
declare(strict_types=1);

namespace SearchForm\Controller\Component;

use Cake\Controller\Component;
use Cake\Utility\Inflector;
use SearchForm\Constant\Param;

class SearchHandlerComponent extends Component
{
    private const FINDER_SEARCH = 'search';

    /**
     * @var \Cake\Controller\Controller
     */
    protected $controller;

    /**
     * Initializes the component and sets the current controller instance.
     *
     * @param array $config The configuration settings provided to the component.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->controller = $this->getController();
    }

    /**
     * Handles the processing of search and pagination for the current controller's model.
     *
     * Determines the appropriate index property or table for the model and applies search and pagination logic.
     * The results are then set to the controller for use within the view.
     *
     * @return void
     */
    public function handle(): void
    {
        $modelName = $this->controller->getName();
        $searchTerm = $this->controller->getRequest()->getQuery(Param::QUERY_PARAM_SEARCH);
        $isSearchTermEmpty = empty($searchTerm);
        if (isset($this->controller->ElasticIndex)) {
            $index = $this->controller->ElasticIndex;
            $results = $this->controller->paginate(!$isSearchTermEmpty
                ? $index->find(self::FINDER_SEARCH, search: $searchTerm)
                : $index->find());
        } else {
            $table = $this->controller->{$modelName};
            $results = $this->controller->paginate(
                $table->hasBehavior('Search') && !$isSearchTermEmpty
                    ? $table->find(self::FINDER_SEARCH, [self::FINDER_SEARCH => $searchTerm])
                    : $table->find('all'),
            );
        }
        $this->controller->set(Inflector::variable(Inflector::pluralize($modelName)), $results);
    }
}
