<style>
 
    .fixTableHead thead th {
      position: sticky;
      top: 0;
    }
    th {
      background: #ABDD93;
      z-index: 20
    }
    .freezColumn {
      position: sticky;
      left: 0;
      background: #ABDD93;
      font-weight: bold;
    }
    .fixedHeader{
      z-index: 50
    }

</style>
<!-- https://blogs.perficient.com/2021/01/18/freezing-row-and-column-in-html-table-using-css/
https://code-boxx.com/freeze-rows-columns-html-tables/
https://www.geeksforgeeks.org/how-to-create-a-table-with-fixed-header-and-scrollable-body/ -->

<div class="card-block table-border-style">
    <div class="table-responsive fixTableHead" style="overflow-x:auto; overflow-y:auto; height:400px">
        <table id="stock-table" class="table table-striped table-bordered table-sm " cellspacing="0"
          width="100%">
          @php
            $columnsHeaders = Config::get('constant.columnHeader');
            $freezColumnClass = '';
          @endphp
                
          <thead>
            <tr>
              @foreach( $columnsHeaders as $key => $value)
                @php
                  if ($loop->first) {
                      $freezColumnClass = 'freezColumn fixedHeader';
                  } else {
                     $freezColumnClass = '';
                  }
                @endphp

                <th class="{{ $freezColumnClass }}">{{ $key }}</th>
              @endforeach
            </tr>
          </thead>
          <tbody style="height: 50px; ">
            @foreach($companies as $row)
            <tr>
              @foreach( $columnsHeaders as $key => $value)
                 @php
                  if ($loop->first) {
                    $freezColumnClass = 'freezColumn';
                  } else {
                     $freezColumnClass = '';
                  }
                @endphp
                <td class="{{ $freezColumnClass }}">{{ $row[$value] }}</td>
              @endforeach
            </tr>
            @endforeach
          </tbody>
        </table>
    </div>
</div>