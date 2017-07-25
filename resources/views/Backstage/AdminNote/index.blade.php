@extends('Backstage.Layouts.layout')

@section('content')
<div class="row">
   <div class="col-xs-12">
       <div class="ibox float-e-margins">
          <div class="ibox-content">

              <a href="/AdminNote/save.html" class="btn btn-primary addbtn"><i class="fa fa-plus"></i> 添加节点</a>
              <div class="table-responsive">
                  <table id="sample-table-1" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>节点ID</th>
                            <th>节点名称</th>
                            <th>更新时间</th>
                            <th>创建时间</th>
                            <th>操作</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($data as $val)
                    <tr>
                        <td>
                            {{$val['admin_note_id']}}
                        </td>
                        <td>
                            {{$val['admin_note_name']}}
                        </td>
                        <td>{{date('Y-m-d H:i:s',$val['admin_note_updated_time'])}}</td>
                        <td>{{date('Y-m-d H:i:s',$val['admin_note_created_time'])}}</td>
                        <td>
                            <a  title="编辑" class="btn  btn-xs btn-outline btn-info" href="/AdminNote/save.html?id={{$val['admin_note_id']}}">
                                <i class="fa fa-pencil"></i>编辑
                            </a>
                            <button title="删除" data-id="{{$val['admin_note_id']}}" data-uri="/AdminNote/del.html" class="btn btn-xs btn-outline btn-danger del">
                                <i class="fa fa-times"></i>删除
                            </button>
                        </td>
                        <td></td>
                    </tr>
                        @if(!empty($val['children']))
                            @foreach ($val['children'] as $c)
                                <tr>
                                    <td style="border: none">

                                    </td>
                                    <td style="text-align: center">
                                        {{$c['admin_note_id']}}
                                    </td>
                                    <td>
                                        {{$c['admin_note_name']}}
                                    </td>
                                    <td>{{date('Y-m-d H:i:s',$c['admin_note_updated_time'])}}</td>
                                    <td>{{date('Y-m-d H:i:s',$c['admin_note_created_time'])}}</td>
                                    <td>
                                        <a  title="编辑" class="btn  btn-xs btn-outline btn-info" href="/AdminNote/save.html?id={{$c['admin_note_id']}}">
                                            <i class="fa fa-pencil"></i>编辑
                                        </a>
                                        <button title="删除" data-id="{{$c['admin_note_id']}}" data-uri="/AdminNote/del.html" class="btn btn-xs btn-outline btn-danger del">
                                            <i class="fa fa-times"></i>删除
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    @endforeach
                    </tbody>
                </table>
               </div>

          </div>
      </div>
   </div>
</div>

@stop

