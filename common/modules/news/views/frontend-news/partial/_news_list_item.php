<div class="card">
    <div class="card-body">
        <h4 class="card-title mt-3"><?= $model->title; ?></h4>
        <p class="card-text"><?= $model->short_text; ?></p>
        <a href="/news/<?= $model->id; ?>">Подробнее</a>
    </div>
</div>