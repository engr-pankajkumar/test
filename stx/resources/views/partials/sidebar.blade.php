<nav class="pcoded-navbar">
    <div class="sidebar_toggle"><a href="#"><i class="icon-close icons"></i></a></div>
    <div class="pcoded-inner-navbar main-menu">
        <!-- <div class="pcoded-navigatio-lavel" data-i18n="nav.category.navigation">Filter</div> -->
        <ul class="pcoded-item pcoded-left-item">
              <div class="form-group form-control-sm">
                <label for="sector-dropdown" placeholder="sds">Sector</label>

                <select class="form-control form-control-sm" id="sector-dropdown">
                  
                </select>

              </div>
              </br>
              <div class="form-group form-control-sm">
                <label for="industry-dropdown" >Industry</label>
                <select class="form-control form-control-sm" id="industry-dropdown">
                 
                </select>
              </div>
              </br>
               </br>
              <div class="form-group form-control-sm">
                <button type="button" class="btn-sm btn-primary applyFilter">Apply</button>
              </div>
              @php
                $columnsHeaders = Config::get('constant.columnHeader');
                //dd($columnsHeaders);
              
              @endphp

              <!-- <div class="form-group form-control-sm">
                <label for="table-columns" >Manage Columns</label>
                {!! Form::select('columns', array_values(array_flip($columnsHeaders)), null, ['class' => 'form-control form-control-sm table-columns', 'multiple' => 'multiple', 'id'=> 'table-columns']) !!}
              </div> -->

              <div class="form-group form-control-sm">
                <label for="table-columnsdsds" >Group</label>

                @php
                $columnsHeadersGroup = Config::get('constant.columnHeaderGroup');
                //dd($columnsHeaders);

                $columnGroup = [];

                foreach($columnsHeadersGroup as $group => $subGroup) {
                    $columnGroup[$group] = array_flip($subGroup);
                }

                //dd($columnGroup);
              
              @endphp

               <!--  {!! Form::select('animal',[
                    'Cats' => ['leopard' => 'Leopard'],
                    'Dogs' => ['spaniel' => 'Spaniel'],
                ], null, ['class' => 'form-control form-control-sm groupTest', 'multiple' =>"multiple"]) !!} -->

                 {!! Form::select('columns', $columnGroup,
                null, ['class' => 'form-control form-control-sm table-columns', 'multiple' => 'multiple', 'id'=> 'table-columns']) !!}
                <!-- <select class="form-control form-control-sm groupTest" multiple="multiple">
                    <optgroup label="Programming Languages">
                        <option value="C">C</option>
                        <option value="C++">C++</option>
                        <option value="Java">Java
                    </optgroup>
                    <optgroup label="Scripting Language">
                        <option value="JavaScript">JavaScript</option>
                        <option value="PHP">PHP</option>
                        <option value="Shell">Shell</option>
                    </optgroup>
                </select> -->
              </div>
             
        </ul>

    </div>


</nav>