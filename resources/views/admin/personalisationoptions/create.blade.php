@extends('admin.layouts.app')
@section('title')Add New Personalisation Options @endsection
@section('page_title')Add New Personalisation Options @endsection
@section('content')
    @include('admin.partials.error_message')
    <form action="{{route('personalisation_option_store')}}" method="post" enctype="multipart/form-data">
        {{csrf_field()}}
        {{method_field('POST')}}
        <div class="row">
            <div class="col-md-12 mt-3 mb-3 card">
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control" value="{{old('name')}}">
                </div>
                <div class="form-group text-right">
                    <button type="button" id="add" class="btn btn-success">Add New Option Value
                    </button>
                </div>
                <table class="table table-bordered" id="dynamicvalue">
                    <tr>
                        <th>Option Value</th>
                        <th>Action</th>
                    </tr>
                    <tr>
                        <td>
                            <input type="text" name="addoption[0][value]" placeholder="Enter Option Value"
                                   class="form-control" id="addoption"/>
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger remove-tr">Remove</button>
                        </td>
                    </tr>

                </table>
                <div class="form-group form-check-inline">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" name="status" value="1" class="custom-control-input"
                               id="status">
                        <label class="custom-control-label" for="status"> Status</label>&nbsp; &nbsp;&nbsp;
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Create</button>
                </div>
            </div>

        </div>
    </form>
@endsection
@section('scripts')
    @include('admin.includes.scripts.personalisationoptions.create')
@endsection