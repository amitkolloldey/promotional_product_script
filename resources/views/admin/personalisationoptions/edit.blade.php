@extends('admin.layouts.app')
@section('title')Edit Personalisation Option @endsection
@section('page_title')Edit Personalisation Option @endsection
@section('content')
    @include('admin.partials.error_message')
    <form action="{{route('personalisation_option_update',$personalisationoption->id)}}" method="post">
        {{csrf_field()}}
        {{method_field('PUT')}}
        <div class="row">
            <div class="col-md-12 mt-3 mb-3 card">
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control"
                           value="{{old('name',$personalisationoption->name)}}">
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
                    @php
                        $count = 1
                    @endphp
                    @foreach($personalisationoption->personalisationOptionValues as $personalisationoptionvalue)
                        <tr>
                            <td>
                                <input type="hidden" name="addoption[{{$count}}][id]"
                                       value="{{$personalisationoptionvalue->id}}"/>
                                <input type="text" name="addoption[{{$count}}][value]" placeholder="Enter Option Value"
                                       class="form-control" id="addoption{{$count}}"
                                       value="{{$personalisationoptionvalue->value}}"/>
                            </td>
                            <td>
                                <button type="button" class="remove_old_option btn btn-danger"
                                        data-oid="{{$personalisationoptionvalue->id}}">
                                    Remove
                                </button>
                            </td>
                        </tr>
                        @php
                            $count++;
                        @endphp
                    @endforeach
                </table>
                <div class="form-group form-check-inline">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" name="status" value="1" class="custom-control-input"
                               id="status" {{$personalisationoption->status == '1' ? 'checked' : ''}}>
                        <label class="custom-control-label" for="status"> Status</label>&nbsp;&nbsp;&nbsp;
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>
        </div>
    </form>
@endsection
@section('scripts')
    @include('admin.includes.scripts.personalisationoptions.edit')
@endsection