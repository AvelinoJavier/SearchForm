<?php

use SearchForm\Constant\Param;

?>
<?= $this->Html->css('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css', ['block' => true]) ?>
<?= $this->Form->create(null, ['valueSources' => 'query']) ?>
<div class="container">
    <div class="row">
        <?= $this->Form->control(Param::QUERY_PARAM_SEARCH, [
            'type' => 'search',
            'placeholder' => __d('search_form', 'Buscar'),
            'label' => false,
            'style' => 'border-top-right-radius: 2px; border-bottom-right-radius: 2px;',
            'templates' => ['inputContainer' => '{{content}}'],
        ]) ?>
        <button type="submit" style="border-top-left-radius: 2px; border-bottom-left-radius: 2px;"><i class="fa-solid fa-search"></i> <?= __d('search_form', 'Buscar') ?></button>
    </div>
</div>
<?= $this->Form->end() ?>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const searchElement = document.getElementById("<?= Param::QUERY_PARAM_SEARCH ?>");
        if (searchElement)
            searchElement.addEventListener("search", function(event) {
                if (!event.target.value)
                    window.location.replace(location.protocol + '//' + location.host + location.pathname);
            });
    });
</script>
