<?= $this->Html->script('SearchForm.script') ?>
<?= $this->Form->create(null, ['valueSources' => 'query']) ?>
<div class="container">
    <div class="row">
        <?= $this->Form->control('search', [
            'type' => 'search',
            'placeholder' => __d('search_form', 'Buscar'),
            'label' => false,
            'style' => 'border-top-right-radius: 0; border-bottom-right-radius: 0;',
            'templates' => [
                'inputContainer' => '{{content}}'
            ]
        ]) ?>
        <button type="submit" style="border-top-left-radius: 0; border-bottom-left-radius: 0;"><i class="fa-solid fa-search"></i> <?= __d('search_form', 'Buscar') ?></button>
    </div>
</div>
<?= $this->Form->end() ?>