@extends('admin.layouts.app')
@section('title')Add New Personalisation Type @endsection
@section('page_title')Add New Personalisation Type @endsection
@section('content')
    @include('admin.partials.error_message')
    <form action="{{route('personalisation_type_store')}}" method="post" id="personalisation_type">
        {{csrf_field()}}
        {{method_field('POST')}}
        <div class="row">
            <div class="col-md-12 mt-3 mb-3 card">
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control" value="{{old('name')}}">
                </div>
                <div class="form-group mt-3">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="custom-control-input"
                               name="is_color_price_included" value="1" {{old('is_color_price_included') == '1' ? "checked" : ""}}>
                        <label class="custom-control-label" for="custom-control-input"> Color Price Included</label>
                    </div>
                </div>

                <div class="form-group mt-3">
                    <h4>Markup (%)</h4>
                </div>

                <div class="form-group">
                    @include('admin.includes.markups.create')
                </div>

                <div class="form-group mt-3">
                    <h3>Personalisation Options</h3>
                </div>
                <div class="row">
                    <div class="form-group mt-3 col-md-4">
                        <h4>Printing Agencies</h4>
                        @foreach($printingagencies as $printingagency)
                            <div class="custom-control custom-switch">
                                <input type="radio" value="{{$printingagency['id']}}" name="printingagency[]" class="custom-control-input" id="printingagency{{$printingagency['id']}}" @if(isset(old('printingagency')['0']) && (old('printingagency')['0'] == $printingagency['id'])) checked @endif>
                                <label class="custom-control-label" for="printingagency{{$printingagency['id']}}"> {{$printingagency['name']}}</label>
                            </div>
                        @endforeach
                    </div>


                    <div class="form-group col-md-8">
                        <div class="form-group row">
                            @foreach($personalisationoptions as $personalisationoption)
                                <div class="col-md-4 mt-3">
                                    @if($personalisationoption['status'] != null)
                                        <h4>{{$personalisationoption['name']}}</h4>
                                        @foreach($personalisationoption['personalisation_option_values'] as $optionvalue)
                                            <input type="hidden" value="{{$personalisationoption['id']}}"  name="option[{{$optionvalue['id']}}][personalisationoption_id]" >
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" value="{{$optionvalue['id']}}" name="option[{{$optionvalue['id']}}][personalisationoptionvalue_id]" class="custom-control-input {{generate_class_name($personalisationoption['name'])}}" id="option{{$optionvalue['id']}}" data-{{generate_class_name ($personalisationoption['name'])}}-id="{{$personalisationoption['id']}}" {{isset(old('option')[ $optionvalue['id'] ]['personalisationoptionvalue_id']) && (old('option')[ $optionvalue['id'] ]['personalisationoptionvalue_id'] == $optionvalue['id']) ? "checked" : ""}}>

                                                <label class="custom-control-label" for="option{{$optionvalue['id']}}"> {{$optionvalue['value']}}</label>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <a href="javascript:void(0);" onclick="generatematrix()" class="btn btn-success">Generate Table</a>
                </div>
                <div class="form-group">
                    <div id="add_matrix">

                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary" id="personalisation_submit">Create</button>
                    <div id="generate_message"></div>
                </div>
            </div>
        </div>
    </form>
@endsection
@section('scripts')
    @include('admin.includes.scripts.personalisationtypes.create')
@endsection

