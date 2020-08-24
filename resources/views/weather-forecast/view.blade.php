@extends('layouts.master')

@section('body')
    <div class="container d-flex min-vh-100">
        <div class="row">
            <div class="loader" style="display: none"></div>
            <div class="col-md-7">
                <div class="row h-100 justify-content-center align-items-center align-content-center" id="city_form">
                    <h3 class="display-4 strong">Weather <br> Forecast</h3>
                    <form method="post" action="{{ route('weather-forecast') }}">
                        @csrf

                        <div class="form-group col-md-12" id="country">
                            <label>Country</label>
                            <select type="text" id="country_id" name="country_id" class="form-control" placeholder="Country">
                                <option value="">Select country</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="form-group col-md-12" id="state" style="display:none">
                            <label>State</label>
                            <select type="text" id="state_id" name="state_id" class="form-control" placeholder="State">
                                <option value="">Select state</option>
                                @foreach($states as $state)
                                    <option value="{{ $state->id }}">{{ $state->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-md-12" id="city">
                            <label>City</label>
                            <select type="text" id="city_id" name="city_id" class="form-control" placeholder="City">
                                <option value="">Select city</option>
                            </select>
                        </div>
                        
                        <div class="form-group col-md-12" id="city-name" style="display:none">
                            <label>City Name</label>
                            <input type="text" id="city_name" name="city_name" class="form-control">
                        </div>

                        <div class="form-group col-md-12 mt-20">
                            <input type="submit" name="send" value="Get Weather" class="btn btn-submit btn-info btn-block">
                        </div>

                        <p class="message text-center"></p>
                    </form>
                </div>
            </div>
        </div>
        <div class="row" id="weather-forecast">
            <div class="col-md-12">
                <div class="row h-100 justify-content-center align-items-center align-content-center">
                    <div class="col-md-12">
                        <section class="ajax-header text-center">
                            <div class="container">
                                <h2><i class="fa fa-calendar fa-md"></i> {{ \Carbon\Carbon::parse()->format('l, d F, Y') }}</h2>
                            </div>
                        </section>
                    </div>
                    <div class="col-md-12">
                        <section class="ajax-section text-center" style="display: none">
                            <div class="container">
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('js')
    <script type="text/javascript">
        $(document).ready(function(){

            $('#country_id').change(function() {
                $('.message').html('');
                $('.ajax-section').hide(150);
                $('#state_id, #city_id, #city_name').val("");
                if ($('#country_id option:selected').text() == "United States") {
                    $('#city-name').hide(150);
                    $('#city_id').html('<option value="" selected>Select city</option>');
                    $('#state, #city').show(150);
                } else {
                    $('#state, #city').hide(150);
                    $('#city-name').show(150);
                }
            });

            $('#city_name').change(function() {
                $('.message').html('');
            });

            $('#state_id').change(function() {
                $('.message').html('');
                $('.ajax-section').hide(150);

                var $city = $('#city_id');
                $.ajax({
                    url: "{{ route('cities.index') }}",
                    data: {
                        state_id: $(this).val()
                    },
                    success: function(data) {
                        $city.html('<option value="" selected>Select city</option>');
                        $.each(data, function(id, value) {
                            $city.append('<option value="'+id+'" data-city="'+value+'">'+value+'</option>');
                        });
                        $('#city').show(150);
                    }
                });
            });

            $(".btn-submit").click(function(e){
                e.preventDefault();
                $('.message').html('');
                $(".loader").fadeIn();
                var cityId = $("#city_id").val();
                var cityName = cityId == "" ? $("#city_name").val() : $("#city_id option:selected").text();

                if (cityName != "") {
                    $.ajax({
                        url:"{{ route('weather-forecast') }}",
                        method: 'post',
                        data: {
                            _token: "{{ csrf_token() }}",
                            city_name: cityName,
                            city_id: cityId
                        },
                        success:function(data){
                            $(".loader").fadeOut();
                            if (data.cod == 200) {
                                var { avgTemp, name, sys, weather } = data;
                                var icon = 'https://s3-us-west-2.amazonaws.com/s.cdpn.io/162656/'+weather[0].icon+'.svg';

                                var $section = $('.ajax-section .container');
                                $section.html('');
                                
                                var content = '<h2 class="city-name" data-name"'+name+','+sys.country+'">';
                                    content +=  '<span>'+name+'</span> ';
                                    content +=  '<sup>'+sys.country+'</sup>';
                                    content +=  '</h2>';
                                    content +=  '<div class="city-temp">'+ avgTemp +'<sup>°C</sup></div>';
                                    content +=  '<figure>';
                                    content +=  '<img class="city-icon" src="' + icon + '" alt="">';
                                    content +=  '<figcaption>'+weather[0].description+'</figcaption>';
                                    content +=  '</figure>';

                                $section.append(content);

                                $('#country_id, #state_id, #city_id, #city_name').val("");
                                $('#city-name, #state').hide(150);
                                $('.ajax-section, #city').show(150);
                            } else {
                                $('.ajax-section').hide(150);
                                $('.message').html('Please select a valid city.');
                            }
                        }
                    });
                } else {
                    $(".loader").fadeOut();
                    $('.message').html('Please select a valid city.');
                }
            });
        });
    </script>
@stop
