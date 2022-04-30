<div class="card-block table-border-style">
    <div class="table-responsive" style="overflow-x:auto;">
        <table id="dtHorizontalVerticalExample" class="table table-striped table-bordered table-sm " cellspacing="0"
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
         <!--  <tbody>
            <tr>
              <td>Tiger</td>
              <td>Nixon</td>
              <td>System Architect</td>
              <td>Edinburgh</td>
              <td>61</td>
              <td>2011/04/25</td>
              <td>$320,800</td>
              <td>5421</td>
              <td>t.nixon@datatables.net</td>
            </tr>
          </tbody> -->
        </table>
    </div>
</div>