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
    .sorting {
      cursor: pointer;
      color: black;
    }
    .sortActive {
      cursor: pointer;
      color: blue;
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

            $columnOrder = $columnOrder ?? [];

            
            
          @endphp
                
          <thead>
            <tr>
              @foreach( $columnsHeaders as $key => $value)
                @php
                  if ($loop->first) {
                      $iconAscClass = 'fa fa-lg fa-sort-alpha-asc';
                      $iconDescClass = 'fa fa-lg fa-sort-alpha-desc';
                      $freezColumnClass = 'freezColumn fixedHeader';
                  } else {
                      $freezColumnClass = '';
                      //$iconAscClass = 'fa fa-long-arrow-up';
                      //$iconDescClass = 'fa fa-long-arrow-down';

                      $iconAscClass = 'fa fa-lg fa-sort-amount-asc';
                      $iconDescClass = 'fa fa-lg fa-sort-amount-desc';
                  }

                  $columnsSort = $columnOrder[$key] ?? '';

                  if($columnsSort == 'asc'){
                      $ascSortActiveClass = 'sortActive';
                      $descSortActiveClass = '';
                  } else if($columnsSort == 'desc') {
                      $ascSortActiveClass = '';
                      $descSortActiveClass = 'sortActive';
                  } else {
                      $ascSortActiveClass = '';
                      $descSortActiveClass = '';
                  }

                @endphp

                <th class="{{ $freezColumnClass }} col-{{$value}}">{{ $key }}
                  <i class="sorting {{ $iconAscClass}} {{ $ascSortActiveClass }}" data-sort="asc" ></i> 
                  <i class="sorting {{ $iconDescClass}} {{ $descSortActiveClass }}" data-sort="desc"></i>
                </th>
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

                  $minStats = $stats[$value]['min'] ?? '';
                  $maxStats = $stats[$value]['max'] ?? '';
                @endphp
                <td class="{{ $freezColumnClass }} col-{{$value}}">{{ $row[$value] }} 
                  @if($row[$value] ==  $minStats) <i class="fa fa-long-arrow-down" data-sort="asc" ></i>  @endif 
                  @if($row[$value] ==  $maxStats) <i class="fa fa-long-arrow-up" data-sort="asc" ></i>  @endif 
                </td>
              @endforeach
            </tr>
            @endforeach
          </tbody>
        </table>
    </div>
</div>