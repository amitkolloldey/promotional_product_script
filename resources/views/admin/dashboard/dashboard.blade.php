@extends('admin.layouts.app')
@section('title')Dashboard @endsection
@section('page_title')Dashboard @endsection
@section('content')
    <div class="row">
        @foreach($models_count as $key => $model)
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-primary-gradient">
                <div class="inner">
                    <h3>{{ $model['count'] }}</h3>
                    <p>{{ ucwords($key) }}</p>
                </div>
                <div class="icon">
                    <i class="fa {{$model['icon']}}"></i>
                </div>
            </div>
        </div>
        @endforeach
        <div class="col-md-12">

            

        </div>
    </div>
@endsection