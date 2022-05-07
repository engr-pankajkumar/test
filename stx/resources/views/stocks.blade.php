@extends('layouts.main')

@push('css')
    <link rel="stylesheet" href="{{asset('plugins/bootstrap-multiselect/dist/css/bootstrap-multiselect.css')}}">

@endpush


@section('content')
<div class="pcoded-wrapper">

    @include('partials.sidebar')

    <div class="pcoded-content">
        <div class="pcoded-inner-content">
            <!-- Main-body start -->
            <div class="main-body">
                <div class="page-wrapper">
                <!-- Page-header start -->
                    
                    <!-- Page-header end -->

                    <!-- Page-body start -->
                    <div class="page-body">
                      <div class="row">
                            <div class="col-sm-12">
                                <!-- Label card start -->
                                
                                    
                                     @php
                                /*
                                @endphp
                                @include('partials.column-labels')
                                 @php
                                 */
                                @endphp
                                    @include('partials.filters')
                                <!-- Label card end -->
                            </div>
                        </div>

                        
                        
                        <!-- Basic table card start -->
                        <div class="card">
                            <div class="card-header">
                                <!-- <h5>Basic table</h5> -->
                                <div class="card-header-right">    
                                  <ul class="list-unstyled card-option">        
                                    <li><i class="icofont icofont-simple-left "></i></li>        
                                    <li><i class="icofont icofont-maximize full-card"></i></li>      <li><i class="icofont icofont-minus minimize-card"></i></li>     <li><i class="icofont icofont-refresh reload-card"></i></li>     <li><i class="icofont icofont-error close-card"></i></li>    
                                  </ul>
                                </div>
                            </div>
                            <div id="company">

                                @php
                                /*
                                @endphp
                                <!-- @include('partials.table') -->
                                 @php
                                 */
                                @endphp


                            </div>
                        </div>
                        <!-- Basic table card end -->
                    </div>
                    <!-- Page-body end -->
                </div>
            </div>
            <!-- Main-body end -->

            @include('partials.right-sidebar-plain')


            </div>
        </div>
    </div>
</div>
@push('js')
<script src="{{asset('plugins/bootstrap-multiselect/dist/js/bootstrap-multiselect.js')}}"></script>
<script src="{{ asset('js/scrip.js') }}"></script>
@endpush

@endsection


