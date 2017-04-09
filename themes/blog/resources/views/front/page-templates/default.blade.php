@extends('theme::front._master')

@section('content')
<div class="container">
    @forelse($posts as $post)
    <article class="article">
        <div class="article__thumbnail">
            <img src="{{ get_image($post->thumbnail) }}">
        </div>
        <div class="article__content">
            <h2 class=""><a href="">{{ $post->title }}</a></h2>
            <span class="label">
                <span class="avatar" style="background-image: url({{ get_image($post->author->avatar) }});"></span>
                {{ $post->author->display_name }}
                <span class="text--gray"> / </span>
                <span class="text--gray">{{ $post->created_at }}</span>
            </span>
            <div class="article__content-body">{!! $post->content !!}</div>
        </div>
    </article>
    @empty
    <p>Không có bài viết hiển thị</p>
    @endforelse
</div>
@endsection
