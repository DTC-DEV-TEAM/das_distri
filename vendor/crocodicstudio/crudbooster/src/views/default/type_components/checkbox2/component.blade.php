<label class='control-label col-sm-2'>{{$form['label']}} {!!($required)?"<span class='text-danger' title='This field is required'>*</span>":"" !!}</label>
<div class='form-group {{$header_group_class}} col-sm-4 {{ ($errors->first($name))?"has-error":"" }}' id='form-group-{{$name}}' style="{{@$form['style']}}">
	<!-- 
	<label class='control-label col-sm-2'>{{$form['label']}} {!!($required)?"<span class='text-danger' title='This field is required'>*</span>":"" !!}</label>
	 -->

	<!--
	<div class="{{$col_width?:'col-sm-8'}}">
	-->

	@if($form['dataenum']!='')
		
		<?php 
			@$value = explode(";",$value);
			@array_walk($value, 'trim');
			$dataenum = $form['dataenum'];
			$dataenum = (is_array($dataenum))?$dataenum:explode(";",$dataenum);
			$counterenum=0;
		?>
		@foreach($dataenum as $k=>$d)
			<?php
				$counterenum++; 
				if(strpos($d, '|')) {
					$val = substr($d, 0, strpos($d, '|'));
					$label = substr($d, strpos($d, '|')+1);
				}else{
					$val = $label = $d;
				}
				$checked = ($value && in_array($val, $value))?"checked":"";									
			?>
			<?php
				if($counterenum%2 == 1)
				{
			?>
					<div class="{{$col_width?:'col-sm-10'}}">
			<?php
				}
			?>
						<div class="checkbox {{$disabled}}">
						  <label>
						    <input type="checkbox" {{$disabled}} {{$checked}} name="{{$name}}[]" value="{{$val}}">{{$label}}
						  </label>
						</div>
			<?php
				if($counterenum%2 == 0)
				{
			?>
						<div class="text-danger">{!! $errors->first($name)?"<i class='fa fa-info-circle'></i> ".$errors->first($name):"" !!}</div>
						<p class='help-block'>{{ @$form['help'] }}</p>
					</div>

			<?php
				}
			?>
		@endforeach
		
	@endif

		<?php 
			if(@$form['datatable']):
				$datatable_array = explode(",",$form['datatable']);
				$datatable_tab = $datatable_array[0];
				$datatable_field = $datatable_array[1];

				$tables = explode('.',$datatable_tab);
				$selects_data = DB::table($tables[0])->select($tables[0].".id");	

				if(\Schema::hasColumn($tables[0],'deleted_at')) {
					$selects_data->where('deleted_at',NULL);
				}

				if(@$form['datatable_where']) {
					$selects_data->whereraw($form['datatable_where']);
				}

				if(count($tables)) {
					for($i=1;$i<=count($tables)-1;$i++) {
						$tab = $tables[$i];
						$selects_data->leftjoin($tab,$tab.'.id','=','id_'.$tab);
					}
				}																			

				$selects_data->addselect($datatable_field);				

				$selects_data = $selects_data->orderby($datatable_field,"asc")->get();

				if($form['relationship_table']) {			
					$foreignKey = CRUDBooster::getForeignKey($table,$form['relationship_table']);	
					$foreignKey2 = CRUDBooster::getForeignKey($datatable_tab,$form['relationship_table']);																																

					$value = DB::table($form['relationship_table'])->where($form['relationship_table'].'.'.$foreignKey,$id);										
					$value = $value->pluck($foreignKey2)->toArray();											
					$counter=0;
					foreach($selects_data as $d) {	
						$counter++;																								
						$checked = (is_array($value) && in_array($d->id, $value))?"checked":"";	
						if($counter%3 == 1)
						{
		?>
							<div class="{{$col_width?:'col-sm-10'}}">
		<?php
						}
						echo "
						<div data-val='$val' class='checkbox $disabled'>
						  <label class='checkbox-inline'>
						    <input type='checkbox' $disabled $checked name='".$name."[]' value='".$d->id."'> ".$d->{$datatable_field}."								    
						  </label>
						</div>";
						if($counter%3 == 0)
						{
		?>
							<div class="text-danger">{!! $errors->first($name)?"<i class='fa fa-info-circle'></i> ".$errors->first($name):"" !!}</div>
							<p class='help-block'>{{ @$form['help'] }}</p>
							</div>
		<?php
						}
					}
				}else{
					@$value = explode(';',$value);
					$counterdataquery=0;
					foreach($selects_data as $d) {
						$counterdataquery++;											
						$val = $d->{$datatable_field};			
						$checked = (is_array($value) && in_array($val, $value))?"checked":"";						
						if($val == '' || !$d->id) continue;
						if($counterdataquery%3 == 1)
						{
		?>
							<div class="{{$col_width?:'col-sm-10'}}">
		<?php
						}
						echo "
						<div data-val='$val' class='checkbox $disabled'>
						  <label class='checkbox-inline'>
						    <input type='checkbox' $disabled $checked name='".$name."[]' value='".$d->id."'> ".$val."							    
						  </label>
						</div>";
						if($counterdataquery%3 == 0)
						{
		?>
							<div class="text-danger">{!! $errors->first($name)?"<i class='fa fa-info-circle'></i> ".$errors->first($name):"" !!}</div>
							<p class='help-block'>{{ @$form['help'] }}</p>
							</div>
		<?php
						}

					}
				}
				
				
			endif;
				if($form['dataquery']){
			
					$query = DB::select(DB::raw($form['dataquery']));
					@$value = explode(';',$value);
					$counter=0;
					if($query) {
						foreach($query as $q) {
							$counter++;
							$val = $q->value;			
							$checked = (is_array($value) && in_array($val, $value))?"checked":"";						
							//if($val == '' || !$d->id) continue;
							if($counter%6 == 1)
							{
		?>
									<div class="{{$col_width?:'col-sm-10'}}">
		<?php
							}
							echo "
							<div data-val='$val' class='checkbox $disabled'>
							  <label>
							    <input type='checkbox' $disabled $checked name='".$name."[]' value='$q->value'> ".$q->label." 								    
							  </label>
							</div>";
							if($counter%6 == 0)
							{
		?>
								<div class="text-danger">{!! $errors->first($name)?"<i class='fa fa-info-circle'></i> ".$errors->first($name):"" !!}</div>
								<p class='help-block'>{{ @$form['help'] }}</p>
								</div>
		<?php
							}
						}
					}
				}
		?>
	<!--
	<div class="text-danger">{!! $errors->first($name)?"<i class='fa fa-info-circle'></i> ".$errors->first($name):"" !!}</div>
	<p class='help-block'>{{ @$form['help'] }}</p>
	</div>
	-->

</div> <!-- form group -->