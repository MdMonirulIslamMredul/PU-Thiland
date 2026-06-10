@include('admin.partials.multilingual-field', [
    'name' => $name,
    'label' => $label,
    'model' => $model ?? null,
    'type' => $type ?? 'text',
    'rows' => $rows ?? 4,
    'colClass' => $colClass ?? 'col-12',
    'requiredLocales' => $requiredLocales ?? ['en', 'bn', 'zh'],
    'locales' => $locales ?? ['en' => 'English', 'bn' => 'Bangla', 'zh' => 'Chinese'],
])
