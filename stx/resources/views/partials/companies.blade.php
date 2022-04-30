<div class="card-block table-border-style">
    <div class="table-responsive" style="overflow-x:auto; overflow-y:auto; height:300px">
        <table id="stock-table" class="table table-striped table-bordered table-sm " cellspacing="0"
          width="100%">
          @php
            $columnsHeaders = Config::get('constant.columnHeader');
          @endphp
                
          <thead>
            <tr>
              @foreach( $columnsHeaders as $key => $value)
                <th>{{ $key }}</th>
              @endforeach
            </tr>
          </thead>
          <tbody style="height: 50px; ">
            @foreach($companies as $row)
            <tr>
              @foreach( $columnsHeaders as $key => $value)
                <td>{{ $row[$value] }}</td>
              @endforeach
            </tr>
            @endforeach
          </tbody>
        </table>
    </div>
</div>