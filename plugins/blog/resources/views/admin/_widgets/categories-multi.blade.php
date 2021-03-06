<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">{{ $title or 'Categories' }}</h3>
        <div class="box-tools">
            <button type="button" class="btn btn-box-tool" data-widget="collapse">
                <i class="fa fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="box-body">
        <div class="scroller height-auto"
             style="max-height: 300px;"
             data-rail-visible="1">
            <div class="multi-choices-widget">
                @if(isset($categories) && (is_array($categories) || $categories instanceof \Illuminate\Support\Collection))
                    @include('blog::admin._widgets._categories-checkbox-option-line', [
                        'categories' => $categories,
                        'value' => (isset($value) ? $value : []),
                        'currentId' => null,
                        'name' => (isset($name) ? $name : '')
                    ])
                @endif
            </div>
        </div>
    </div>
</div>
