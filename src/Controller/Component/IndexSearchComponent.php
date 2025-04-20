<?php
declare(strict_types=1);

namespace SearchForm\Controller\Component;

use Cake\Controller\Component;
use Cake\Utility\Inflector;

/**
 * IndexSearchComponent
 *
 * Automatically handles paginated search for the current model if SearchBehavior is enabled.
 */
class IndexSearchComponent extends Component
{
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
     * Handles a paginated index with optional search support.
     *
     * @return void
     */
    public function handleIndex(): void
    {
        $modelName = $this->controller->getName();
        $table = $this->controller->{$modelName};
        $this->controller->set(Inflector::variable(Inflector::pluralize($modelName)), $this->controller->paginate(
            $table->hasBehavior('Search')
            ? $table->find('search', search: $this->controller->getRequest()->getQueryParams())
            : $table->find('all'),
        ));
    }
}
