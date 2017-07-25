@extends('Backstage.Layouts.layout')

@section('content')
<div class="row">
   <div class="col-xs-12">
      <div class="ibox float-e-margins">
          <div class="ibox-content">
              <div class="row">
                  {!! Form::model('',['class' => 'form-horizontal searchposition','method'=>'GET'])!!}
                  <?php echo Form::getValueAttribute('admin_role_name') ?>
                  <div class="col-sm-2 m-b-xs">
                      {!! Form::text('param[admin_role_name]',issetParam($param,'admin_role_name'), ['placeholder'=>'角色名称','class'=>'input-sm form-control','id'=>'form-field-1']) !!}
                  </div>
                  <div class="col-sm-5 m-b-xs">
                      <span class="float-left hintparam">角色状态:</span>
                      <span class="float-left">
                          {!! Form::select('param[admin_role_status]',\App\Http\Models\Backstage\AdminUser::getStatusName(true),issetParam($param,'admin_role_status'),['class'=>'form-control m-b','id'=>'admin_role_status']) !!}
                      </span>
                  </div>
                  <div class="col-sm-1 m-b-xs">
                      {!! Form::button('搜索 <i class="fa fa-search"></i> ',['class'=>'btn btn-info  btn-sm searchbtn','type'=>'submit']) !!}
                  </div>
                  {!! Form::close() !!}
              </div>
              <a href="/AdminRole/save.html" class="btn btn-primary addbtn"><i class="fa fa-plus"></i> 添加角色</a>

              <div class="table-responsive">
                  <table id="sample-table-1" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>角色ID</th>
                            <th>角色名称</th>
                            <th>成员数</th>
                            <th>角色权限</th>
                            <th>更新时间</th>
                            <th>创建时间</th>
                            <th>角色状态</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $getStatusName =  \App\Http\Models\Backstage\AdminRole::getStatusName();?>
                    @foreach ($data as $val)
                    <tr>
                        <td>
                            {{$val->admin_role_id}}
                        </td>
                        <td>{{$val->admin_role_name}}</td>
                        <td>{{$val->admin_role_number}}</td>
                        <td style="max-width: 300px;">{{$val->admin_note_names}}</td>
                        <td>
                            {{$val->admin_role_updated_time}}
                        </td>
                        <td>
                            {{$val->admin_role_created_time}}
                        </td>
                        <td>
                            {!! Form::select('',
                            $getStatusName,
                            $val->admin_role_status,
                            ['class'=>'form-control status','data-name'=>'admin_role_status','data-id'=>$val->admin_role_id,'data-uri'=>Url('AdminRole/status.html')]
                            ) !!}
                        </td>
                        <td>
                            <a  title="编辑" class="btn  btn-xs btn-outline btn-info " href="/AdminRole/save.html?id={{$val->admin_role_id}}">
                                <i class="fa fa-pencil"></i>编辑
                            </a>
                            <button title="删除" data-id="{{$val->admin_role_id}}" data-uri="/AdminRole/del.html" class="btn  btn-xs  btn-outline btn-danger del">
                                <i class="fa fa-times"></i>删除
                            </button>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
               </div>
              <div class="flypage">
                  {!!  $data->appends(['param'=>$param])->render() !!}
              </div>
          </div>
      </div>
   </div>
</div>
@stop

