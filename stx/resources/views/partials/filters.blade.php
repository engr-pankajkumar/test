 <div class="card">
    <!-- <div class="card-header">
        <div class="card-header-left">
            <h5>Columns</h5>
        </div>
    </div> -->
    <div class="card-block">
        <!-- labels -->
        <div class="row">
            <div class="col-md-12">
             <form>
              <div class="form-row">
                @php
                  $filters = Config::get('constant.filter');
                @endphp
                @foreach($filters as $key => $val)
                    <div class="form-group col-md-2">
                      <label for="inputEmail4">{{ $key }}</label>
                         @php
                            $options = AppHelper::createDropDown($val);
                            $fields = $key;
                            $id = $key;
                         @endphp
                        {!! Form::select($key, $options, null, ['class' => 'form-control sfilter', 'multiple' => 'multiple', 'id'=> $key]) !!}
                    </div>

                @endforeach
              </div>

            </form>
            </div>
           
        </div>
    </div>
</div>