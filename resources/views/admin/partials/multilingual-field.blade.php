@php
    $type = $type ?? 'text';
    $rows = $rows ?? 4;
    $colClass = $colClass ?? 'col-12';
    $requiredLocales = $requiredLocales ?? ['en', 'bn', 'zh'];
    $locales = $locales ?? [
        'en' => __('admin.locales.en'),
        'bn' => __('admin.locales.bn'),
        'zh' => __('admin.locales.zh'),
    ];

    $modelValues = [];
    foreach ($locales as $localeKey => $localeLabel) {
        if (!empty($model) && method_exists($model, 'getTranslation')) {
            $modelValues[$localeKey] = (string) $model->getTranslation($name, $localeKey, false);
        }
    }

    $oldValues = old($name, []);
    if (!is_array($oldValues)) {
        $oldValues = [];
    }

    $values = array_merge($modelValues, $oldValues);
    $groupId = $name . '-' . str_replace('.', '-', uniqid('', true));
@endphp

<div class="{{ $colClass }}">
    <label class="form-label fw-semibold d-block">{{ $label }}</label>

    <ul class="nav nav-tabs" role="tablist">
        @foreach ($locales as $localeKey => $localeLabel)
            @php
                $tabId = $groupId . '-' . $localeKey;
                $isActive = $loop->first;
            @endphp
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $isActive ? 'active' : '' }}" id="{{ $tabId }}-tab" data-bs-toggle="tab"
                    data-bs-target="#{{ $tabId }}" type="button" role="tab"
                    aria-controls="{{ $tabId }}" aria-selected="{{ $isActive ? 'true' : 'false' }}">
                    {{ $localeLabel }}
                    @if (in_array($localeKey, $requiredLocales, true))
                        <span class="text-danger">*</span>
                    @endif
                </button>
            </li>
        @endforeach
    </ul>

    <div class="tab-content border border-top-0 rounded-bottom p-3 bg-white">
        @foreach ($locales as $localeKey => $localeLabel)
            @php
                $tabId = $groupId . '-' . $localeKey;
                $fieldName = $name . '[' . $localeKey . ']';
                $fieldError = $name . '.' . $localeKey;
                $isActive = $loop->first;
                $value = old($fieldError, $values[$localeKey] ?? '');
                $isRequired = in_array($localeKey, $requiredLocales, true);
            @endphp

            <div class="tab-pane fade {{ $isActive ? 'show active' : '' }}" id="{{ $tabId }}" role="tabpanel"
                aria-labelledby="{{ $tabId }}-tab">
                @if ($type === 'textarea' || $type === 'richtext')
                    <textarea name="{{ $fieldName }}"
                        class="form-control @if ($type === 'richtext') js-ckeditor @endif @error($fieldError) is-invalid @enderror"
                        rows="{{ $rows }}" @if ($isRequired && $type !== 'richtext') required @endif>{{ $value }}</textarea>
                @else
                    <input type="text" name="{{ $fieldName }}"
                        class="form-control @error($fieldError) is-invalid @enderror" value="{{ $value }}"
                        @if ($isRequired) required @endif>
                @endif

                @error($fieldError)
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
        @endforeach
    </div>
</div>

@once
    @push('scripts')
        <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.js-ckeditor').forEach(function(editorElement) {
                    if (editorElement.dataset.editorInitialized === '1') {
                        return;
                    }

                    ClassicEditor.create(editorElement).then(function() {
                        editorElement.dataset.editorInitialized = '1';
                    }).catch(function(error) {
                        console.error(error);
                    });
                });

                document.querySelectorAll('form').forEach(function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity()) {
                            return;
                        }

                        var invalidField = form.querySelector(':invalid');
                        if (!invalidField) {
                            return;
                        }

                        var hiddenPane = invalidField.closest('.tab-pane');
                        if (!hiddenPane || hiddenPane.classList.contains('show')) {
                            return;
                        }

                        var tabTrigger = document.querySelector('[data-bs-target="#' + hiddenPane.id +
                            '"], [href="#' + hiddenPane.id + '"]');
                        if (tabTrigger) {
                            var tab = bootstrap.Tab.getOrCreateInstance(tabTrigger);
                            tab.show();
                            setTimeout(function() {
                                invalidField.focus();
                            }, 100);
                        }
                    });
                });
            });
        </script>
    @endpush
@endonce
