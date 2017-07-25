@extends('Backstage.Layouts.layout')

@section('content')
<div class="row">
   <div class="col-xs-12">
       <div class="ibox float-e-margins">
          <div class="ibox-content">
              <div class="row">
                  {!! Form::model('',['class' => 'form-horizontal searchposition','method'=>'GET'])!!}
                  <div class="col-sm-2 m-b-xs">
                      {!! Form::text('param[admin_user_account]',issetParam($param,'admin_user_account'), ['placeholder'=>'账号名称','class'=>'input-sm form-control','id'=>'admin_user_account']) !!}
                  </div>
                  <div class="col-sm-2 m-b-xs">
                      {!! Form::text('param[admin_user_name]',issetParam($param,'admin_user_name'), ['placeholder'=>'真实姓名','class'=>'input-sm form-control','id'=>'admin_user_name']) !!}
                  </div>
                  <div class="col-sm-2 m-b-xs">
                      {!! Form::text('param[admin_user_phone]',issetParam($param,'admin_user_phone'), ['placeholder'=>'手机号','class'=>'input-sm form-control','id'=>'admin_user_phone']) !!}
                  </div>
                  <div class="col-sm-2 m-b-xs">
                      <span class="float-left hintparam">角色:</span>
                      <span class="float-left">
                      {!! Form::select('param[admin_user_role]',\App\Http\Models\Backstage\AdminRole::queryRoleSelectList(true),issetParam($param,'admin_user_role'),['class'=>'form-control m-b','id'=>'admin_user_role']) !!}
                      </span>
                  </div>

                  <div class="col-sm-2 m-b-xs">
                      <span class="float-left hintparam">状态:</span>
                      <span class="float-left">
                          {!! Form::select('param[admin_user_status]',\App\Http\Models\Backstage\AdminRole::getStatusName(true),issetParam($param,'admin_user_status'),['class'=>'form-control m-b','id'=>'admin_user_status']) !!}
                      </span>
                  </div>
                  <div class="col-sm-1 m-b-xs">
                      {!! Form::button('搜索 <i class="fa fa-search"></i> ',['class'=>'btn btn-info  btn-sm searchbtn','type'=>'submit']) !!}
                  </div>
                  {!! Form::close() !!}
              </div>
              <a href="/AdminUser/save.html" class="btn btn-primary addbtn"><i class="fa fa-plus"></i> 添加账号</a>
              <div class="table-responsive">
                  <table id="sample-table-1" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>后台账号</th>
                            <th>真实姓名</th>
                            <th>头像</th>
                            <th>角色</th>
                            <th>手机</th>
                            <th>邮箱</th>
                            <th>更新时间</th>
                            <th>创建时间</th>
                            <th>状态</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                     $queryRoleSelectList = \App\Http\Models\Backstage\AdminRole::queryRoleSelectList();
                     $getStatusName = \App\Http\Models\Backstage\AdminUser::getStatusName();
                    ?>
                    @foreach ($data as $val)
                    <tr>
                        <td>
                            {{$val->admin_user_account}}
                        </td>
                        <td>{{$val->admin_user_name}}</td>
                        <td>
                            <img alt="image" class="img-circle" style="width: 24px;height: 24px;" src="{{$val->admin_user_headimg}}">
                        </td>
                        <td>
                            {!! Form::select('',
                            $queryRoleSelectList,
                            $val->admin_role_id,
                            ['class'=>'form-control rolestatus','data-name'=>'admin_role_id','data-id'=>$val->admin_user_id,'data-uri'=>Url('AdminUser/rolestatus.html')]
                            ) !!}
                        </td>
                        <td>{{$val->admin_user_phone}}</td>
                        <td>{{$val->admin_user_mail}}</td>
                        <td>{{$val->admin_user_updated_time}}</td>
                        <td>{{$val->admin_user_created_time}}</td>
                        <td>
                            {!! Form::select('',
                            $getStatusName,
                            $val->admin_user_status,
                            ['class'=>'form-control status','data-name'=>'admin_user_status','data-id'=>$val->admin_user_id,'data-uri'=>Url('AdminUser/status.html')]
                            ) !!}
                        </td>
                        <td>
                            <a  title="编辑" class="btn  btn-xs btn-outline btn-info" href="/AdminUser/save.html?id={{$val->admin_user_id}}">
                                <i class="fa fa-pencil"></i>编辑
                            </a>
                            <button title="删除" data-id="{{$val->admin_user_id}}" data-uri="/AdminUser/del.html" class="btn btn-xs btn-outline btn-danger del">
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
<script>
    $('.rolestatus').change(function () {
        var $this = $(this);
        $this.attr('disabled', 'disabled');
        var id = $this.data('id');
        var status = $this.val();
        var name = $this.data('name');
        var uri = $this.data('uri');
        var _token = $('#_token').val();
        var param = {};
        param[name] = status;
        $.post(uri, {id:id,param:param,_token: _token}, function (result) {
            if (result['status'] == 1) {
                layer.msg('修改成功', { icon: 1});
            }else {
                layer.msg(result['msg'], { icon: 2});
            }
            $this.attr('disabled', false);
        });

    });
</script>
@stop

