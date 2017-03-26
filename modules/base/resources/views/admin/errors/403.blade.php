@extends('base::admin._master')

@section('style')
<style>
    .column.main {
        text-align: center;
    }
    .column.main h3, .column.main p{
        font-family: cursive;
    }
    .column.main h3 {
        font-size: 45px;
    }
</style>
@endsection

@section('content')
    <div class="layout-1columns">
        <div class="column main">
            <img src="/libraries/images/pic_403_404.png" alt="403 fobbiden">
            <h3>Fobbiden</h3>
            <p>Bạn chưa đủ điều kiện để truy cập vào trang này. Quay lại sau!</p>
        </div>
    </div>
@endsection
