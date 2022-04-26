# SearchForm plugin for CakePHP
Plugin that shows a form that implement the friendsofcake/search plugin in a element component.

## Installation
```
composer require avelinojavier/cakephp-font-awesome-loader
composer require avelinojavier/cakephp-jquery-loader
composer require avelinojavier/cakephp-search-form
```

## Usage
You can paste this code in your default layout header.
```
<?= $this->element('FontAwesomeLoader.load') ?>
<?= $this->element('JqueryLoader.load') ?>
```
Use this code to display the search form
```
<?= $this->element('SearchForm.display') ?>
```