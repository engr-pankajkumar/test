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
              @php
                $columnsHeaders = Config::get('constant.columnHeader');
              @endphp
                @foreach( $columnsHeaders as $key => $value)
                  <a href="javascript:void(0);" class="badge badge-success btn-sm badge-cols badge-secondary draggable" data-key="{{ $loop->iteration }}">{{ $key }}</a>
                @endforeach
              <!-- <a href="#" class="badge badge-primary btn-md">Primary</a>
              <a href="#" class="badge badge-secondary btn-md">Secondary</a>
              <a href="#" class="badge badge-success btn-md">Success</a>
              <a href="#" class="badge badge-danger btn-md">Danger</a>
              <a href="#" class="badge badge-warning btn-md">Warning</a>
              <a href="#" class="badge badge-info btn-md">Info</a>
              <a href="#" class="badge badge-light btn-md">Light</a>
              <a href="#" class="badge badge-dark btn-sm">Dark</a>
              <a href="#" class="badge badge-dark ">Dark</a> -->
            </div>
        </div>
    </div>
</div>