<div class="col-lg-3 col-md-4 col-sm-6">
    <div class="small-box bg-red">
        <div class="inner">
            <h3>{{ $count or 0 }}</h3>
            <p>Pages</p>
        </div>
        <div class="icon">
            <i class="icon-notebook"></i>
        </div>
        <a href="{{ route('pages.index.get') }}" class="small-box-footer">
            More info <i class="fa fa-arrow-circle-right"></i>
        </a>
    </div>
</div>
