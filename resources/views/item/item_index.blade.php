@extends('crudbooster::admin_template')
@section('content')

<div class="box">
    <div class="box-header">
        @if($button_bulk_action && ( ($button_delete && CRUDBooster::isDelete()) || $button_selected) )
            <div class="pull-{{ trans('crudbooster.left') }}">
                <div class="selected-action" style="display:inline-block;position:relative;">
                    <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i
                                class='fa fa-check-square-o'></i> {{trans("crudbooster.button_selected_action")}}
                        <span class="fa fa-caret-down"></span></button>
                    <ul class="dropdown-menu">
                        @if($button_delete && CRUDBooster::isDelete())
                            <li><a href="javascript:void(0)" data-name='delete' title='{{trans('crudbooster.action_delete_selected')}}'><i
                                            class="fa fa-trash"></i> {{trans('crudbooster.action_delete_selected')}}</a></li>
                        @endif

                        @if($button_selected)
                            @foreach($button_selected as $button)
                                <li><a href="javascript:void(0)" data-name='{{$button["name"]}}' title='{{$button["label"]}}'><i
                                                class="fa fa-{{$button['icon']}}"></i> {{$button['label']}}</a></li>
                            @endforeach
                        @endif

                    </ul><!--end-dropdown-menu-->
                </div><!--end-selected-action-->
            </div><!--end-pull-left-->
        @endif
            <div class="box-tools pull-{{ trans('crudbooster.right') }}" style="position: relative;margin-top: -5px;margin-right: -10px">

                @if($button_filter)
                    <a style="margin-top:-23px" href="javascript:void(0)" id='btn_advanced_filter' data-url-parameter='{{$build_query}}'
                        title='{{trans('crudbooster.filter_dialog_title')}}' class="btn btn-sm btn-default {{(Request::get('filter_column'))?'active':''}}">
                        <i class="fa fa-filter"></i> {{trans("crudbooster.button_filter")}}
                    </a>
                @endif

                <form method='get' style="display:inline-block;width: 260px;" action='{{Request::url()}}'>
                    <div class="input-group">
                        <input type="text" name="q" value="{{ Request::get('q') }}" class="form-control input-sm pull-{{ trans('crudbooster.right') }}"
                            placeholder="{{trans('crudbooster.filter_search')}}"/>
                        {!! CRUDBooster::getUrlParameters(['q']) !!}
                        <div class="input-group-btn">
                            @if(Request::get('q'))
                                <?php
                                $parameters = Request::all();
                                unset($parameters['q']);
                                $build_query = urldecode(http_build_query($parameters));
                                $build_query = ($build_query) ? "?".$build_query : "";
                                $build_query = (Request::all()) ? $build_query : "";
                                ?>
                                <button type='button' onclick='location.href="{{ CRUDBooster::mainpath().$build_query}}"' title="{{trans('crudbooster.button_reset')}}" class='btn btn-sm btn-warning'><i class='fa fa-ban'></i></button>
                            @endif
                            <button type='submit' class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                        </div>
                    </div>
                </form>

                <form method='get' id='form-limit-paging' style="display:inline-block" action='{{Request::url()}}'>
                    {!! CRUDBooster::getUrlParameters(['limit']) !!}
                    <div class="input-group">
                        <select onchange="$('#form-limit-paging').submit()" name='limit' style="width: 56px;" class='form-control input-sm'>
                            <option {{($limit==5)?'selected':''}} value='5'>5</option>
                            <option {{($limit==10)?'selected':''}} value='10'>10</option>
                            <option {{($limit==20)?'selected':''}} value='20'>20</option>
                            <option {{($limit==25)?'selected':''}} value='25'>25</option>
                            <option {{($limit==50)?'selected':''}} value='50'>50</option>
                            <option {{($limit==100)?'selected':''}} value='100'>100</option>
                            <option {{($limit==200)?'selected':''}} value='200'>200</option>
                        </select>
                    </div>
                </form>

            </div>

            <br style="clear:both"/>
    </div>
    <div class="box-body table-responsive no-padding">

        <form id='form-table' method='post' action='{{CRUDBooster::mainpath("action-selected")}}'>
            <input type='hidden' name='button_name' value=''/>
            <input type='hidden' name='_token' value='{{csrf_token()}}'/>
            <table id="table_dashboard" class='table table-hover table-striped table-bordered table_dashboard'>
                <thead>
                    <tr>
                        <th width='3%'><input type='checkbox' id='checkall'/></th>
                        <th>Digits Code</th>
                        <th>UPC Code</th>
                        <th>Supplier Item Code</th>
                        <th>Item Description</th>
                        <th>Brand</th>
                        <th>Category</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $row)
                    <tr>
                        <td width='3%'><input type='checkbox' class="checkbox" name="checkbox[]" value="{{ $row->id }}"/></td>
                        <td>{{$row->digits_code}}</td>
                        <td>{{$row->upc_code}}</td>
                        <td>{{$row->supplier_itemcode}}</td>
                        <td>{{$row->item_description}}</td>
                        <td>{{$row->brand->brand_description}}</td>
                        <td>{{$row->category->category_description}}</td>
                        <td>
                        <!-- To make sure we have read access, wee need to validate the privilege -->
                        @if(CRUDBooster::isRead() && $button_detail)
                            <a class='btn btn-xs btn-detail' title='{{trans("crudbooster.action_detail_data")}}' href='{{CRUDBooster::mainpath("detail/".$row->$pk)."?return_url=".urlencode(Request::fullUrl())}}'><i class='fa fa-eye'></i></a>
                        @endif

                        @if(CRUDBooster::isUpdate() && $button_edit)
                            <a class='btn btn-xs btn-edit' title='{{trans("crudbooster.action_edit_data")}}' href='{{CRUDBooster::mainpath("edit/".$row->$pk)."?return_url=".urlencode(Request::fullUrl())."&parent_id=".g("parent_id")."&parent_field=".$parent_field}}'><i class='fa fa-pencil'></i></a>
                        @endif

                        @if(CRUDBooster::isDelete() && $button_delete)
                            <?php $url = CRUDBooster::mainpath("delete/".$row->$pk);?>
                            <a class='btn btn-xs btn-delete' title='{{trans("crudbooster.action_delete_data")}}' href='javascript:;' onclick='{{CRUDBooster::deleteConfirm($url)}}'><i class='fa fa-trash'></i></a>
                        @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </form>
        <!-- ADD A PAGINATION -->
        <div class="col-md-8">{!! urldecode(str_replace("/?","?",$items->appends(Request::all())->render())) !!}</div>
        <?php
            $from = $items->count() ? ($items->perPage() * $items->currentPage() - $items->perPage() + 1) : 0;
            $to = $items->perPage() * $items->currentPage() - $items->perPage() + $items->count();
            $total = $items->total();
        ?>
        <div class="col-md-4" style="margin:30px 0;"><span class="pull-right">{{ trans("crudbooster.filter_rows_total") }} : {{ $from }} {{ trans("crudbooster.filter_rows_to") }} {{ $to }} {{ trans("crudbooster.filter_rows_of") }} {{ $total }}</span></div>

    </div>
</div>

@endsection

@push('bottom')
    <script type="text/javascript">
        $(document).ready(function () {
            var $window = $(window);

            function checkWidth() {
                var windowsize = $window.width();
                if (windowsize > 500) {
                    console.log(windowsize);
                    $('#box-body-table').removeClass('table-responsive');
                } else {
                    console.log(windowsize);
                    $('#box-body-table').addClass('table-responsive');
                }
            }

            checkWidth();
            $(window).resize(checkWidth);

            $('.selected-action ul li a').click(function () {
                var name = $(this).data('name');
                $('#form-table input[name="button_name"]').val(name);
                var title = $(this).attr('title');

                swal({
                        title: "{{trans("crudbooster.confirmation_title")}}",
                        text: "{{trans("crudbooster.alert_bulk_action_button")}} " + title + " ?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#008D4C",
                        confirmButtonText: "{{trans('crudbooster.confirmation_yes')}}",
                        closeOnConfirm: false,
                        showLoaderOnConfirm: true
                    },
                    function () {
                        $('#form-table').submit();
                    });

            })

            $('table tbody tr .button_action a').click(function (e) {
                e.stopPropagation();
            })
        });
    </script>
@endpush