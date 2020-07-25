@extends('admin.layouts.app')
@section('title')View Message @endsection
@section('page_title')View Message from {{$message->fname}} @endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-3">
                    <p><strong>First Name: </strong></p>
                </div>
                <div class="col-md-9">
                    <p>{{$message->fname}}</p>
                </div>

                <div class="col-md-3">
                    <p><strong>Last Name: </strong></p>
                </div>
                <div class="col-md-9">
                    <p>{{$message->lname}}</p>
                </div>

                <div class="col-md-3">
                    <p><strong>Email: </strong></p>
                </div>
                <div class="col-md-9">
                    <p>{{$message->email}}</p>
                </div>

                <div class="col-md-3">
                    <p><strong>Subject: </strong></p>
                </div>
                <div class="col-md-9">
                    <p>{{$message->subject}}</p>
                </div>

                <div class="col-md-3">
                    <p><strong>Subject: </strong></p>
                </div>
                <div class="col-md-9">
                    <p>{{$message->subject}}</p>
                </div>

                <div class="col-md-3">
                    <p><strong>Phone: </strong></p>
                </div>
                <div class="col-md-9">
                    <p>{{ $message->phone ?  $message->phone : 'Not Given' }}</p>
                </div>

                <div class="col-md-3">
                    <p><strong>Message: </strong></p>
                </div>
                <div class="col-md-9">
                    <p>{{ $message->message }}</p>
                </div>

                <div class="col-md-3 ">
                    <form action="{{route('message_delete', $message->id )}}" method="POST" class="mt-5 ">
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}
                        <div class="form-group">
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </div>
                    </form>
                </div>
                <div class="col-md-9">

                </div>

            </div>
        </div>
    </div>
@endsection