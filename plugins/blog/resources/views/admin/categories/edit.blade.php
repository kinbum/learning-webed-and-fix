@extends('base::admin._master')

@section('css')

@endsection

@section('js')

@endsection

@section('script')
    <script type="text/javascript">
        $(document).ready(function () {
            WebEd.ckeditor($('.js-ckeditor'));
        });
    </script>
@endsection

@section('content')
    {!! Form::open(['class' => 'js-validate-form', 'url' => route('blog.categories.edit.post', ['id' => $object->id])]) !!}
    <div class="layout-2columns sidebar-right">
        <div class="column main">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Basic information</h3>
                    <div class="box-tools">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <label class="control-label">
                            <b>Title</b>
                            <span class="required">*</span>
                        </label>
                        <input required type="text" name="title"
                               class="form-control"
                               value="{{ $object->title or '' }}"
                               autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label class="control-label">
                            <b>Friendly slug</b>
                            <span class="required">*</span>
                        </label>
                        <input type="text" name="slug"
                               class="form-control"
                               value="{{ $object->slug or '' }}" autocomplete="off">
                    </div>
                    @if($object->slug)
                        <div class="form-group">
                            <label class="control-label">
                                <b>Visit page:</b>
                            </label>
                            <a href="{{ route('front.web.resolve-blog.get', ['slug' => $object->slug]) }}" target="_blank">{{ route('front.web.resolve-blog.get', ['slug' => $object->slug]) }}</a>
                        </div>
                    @endif
                    <div class="form-group">
                        <label class="control-label">
                            <b>Content</b>
                        </label>
                        <textarea name="content"
                                  class="form-control js-ckeditor">{{ $object->content or '' }}</textarea>
                    </div>
                </div>
            </div>
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">SEO</h3>
                    <div class="box-tools">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <label class="control-label">
                            <b>Keywords</b>
                        </label>
                        <input type="text" name="keywords"
                               class="form-control js-tags-input"
                               value="{{ $object->keywords or '' }}" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label class="control-label">
                            <b>Description</b>
                        </label>
                        <textarea name="description"
                                  class="form-control"
                                  rows="5">{{ $object->description or '' }}</textarea>
                    </div>
                </div>
            </div>
            @php do_action('meta_boxes', 'main', 'blog.categories.edit', $object) @endphp
        </div>
        <div class="column right">
            @php do_action('meta_boxes', 'top-sidebar', 'blog.categories.edit', $object) @endphp
            @include('base::admin._widgets.page-templates', [
                'name' => 'page_template',
                'templates' => get_templates('Category'),
                'selected' => isset($object) ? $object->page_template : '',
            ])
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Parent category</h3>
                    <div class="box-tools">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse">
                            <i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body">
                    {!! Form::select(
                            'parent_id',
                            $categories,
                            (isset($object->parent_id) ? $object->parent_id : null),
                            ['class' => 'form-control']
                        )
                    !!}
                </div>
            </div>
            @include('base::admin._widgets.thumbnail', [
                'name' => 'thumbnail',
                'value' => (isset($object->thumbnail) ? $object->thumbnail : null)
            ])
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Sort order</h3>
                    <div class="box-tools">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <label class="control-label">
                            <b>Sort order</b>
                            <span class="required">*</span>
                        </label>
                        <input type="text" name="order"
                               class="form-control"
                               value="{{ $object->order or '0' }}" autocomplete="off">
                    </div>
                </div>
            </div>
            @php do_action('meta_boxes', 'bottom-sidebar', 'blog.categories.edit', $object) @endphp
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Publish content</h3>
                    <div class="box-tools">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <label class="control-label">
                            <b>Status</b>
                            <span class="required">*</span>
                        </label>
                        {!! form()->select('status', [
                            'activated' => 'Activated',
                            'disabled' => 'Disabled',
                        ], (isset($object->status) ? $object->status : ''), ['class' => 'form-control']) !!}
                    </div>
                    <div class="text-right">
                        <button class="btn btn-primary" type="submit">
                            <i class="fa fa-check"></i> Save
                        </button>
                        <button class="btn btn-success" type="submit"
                                name="_continue_edit" value="1">
                            <i class="fa fa-check"></i> Save & continue
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
@endsection
