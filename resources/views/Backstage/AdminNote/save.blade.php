@extends('Backstage.Layouts.layout')

@section('content')

<div class="row">
    <div class="col-xs-12">
        <div class="ibox float-e-margins">
            <div class="ibox-content">
                {!! Form::model($data,['class' => 'form-horizontal searchposition','method'=>'POST'])!!}
                {!! Form::hidden('id', $data->admin_note_id,['id'=>'admin_note_id']) !!}

                <div class="form-group">
                    {!! Form::label('admin_note_name', '节点名称:', ['class'=>'col-sm-2 control-label']) !!}

                    <div class="col-sm-3">
                        {!! Form::text('param[admin_note_name]',$data->admin_note_name, ['class'=>'form-control','id'=>'admin_note_name','maxlength'=>30]) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('admin_note_zh_name', '节点备注:', ['class'=>'col-sm-2 control-label']) !!}

                    <div class="col-sm-3">
                        {!! Form::text('param[admin_note_zh_name]',$data->admin_note_zh_name, ['class'=>'form-control','id'=>'admin_note_zh_name','maxlength'=>30]) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('admin_note_default', '是否换行:', ['class'=>'col-sm-2 control-label']) !!}

                    <div class="col-sm-3">
                        {!! Form::text('param[admin_note_default]',$data->admin_note_default, ['class'=>'form-control','id'=>'admin_note_default','maxlength'=>30]) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('admin_box', '是否显示分割线:', ['class'=>'col-sm-2 control-label']) !!}

                    <div class="col-sm-3">
                        {!! Form::text('param[admin_box]',$data->admin_box, ['class'=>'form-control','id'=>'admin_box','maxlength'=>30]) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('admin_note_controller', '路由name:', ['class'=>'col-sm-2 control-label']) !!}

                    <div class="col-sm-3">
                        {!! Form::text('param[admin_note_controller]',$data->admin_note_controller, ['class'=>'form-control','id'=>'admin_note_controller','maxlength'=>30]) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('admin_note_action', '父级name:', ['class'=>'col-sm-2 control-label']) !!}
                    <label>如果是父级,改字段存它下面所有的子集name英文,号分割。如果是自己,改字段存他的父级name</label>
                    <div class="col-sm-3">
                        {!! Form::text('param[admin_note_action]',$data->admin_note_action, ['class'=>'form-control','id'=>'admin_note_action','maxlength'=>30]) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('admin_admin_show', '是否超级管理员可见:', ['class'=>'col-sm-2 control-label']) !!}

                    <div class="col-sm-3">
                        {!! Form::text('param[admin_admin_show]',$data->admin_admin_show, ['class'=>'form-control','id'=>'admin_admin_show','maxlength'=>30]) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('admin_note_parent_id', '父级ID:', ['class'=>'col-sm-2 control-label']) !!}

                    <div class="col-sm-3">
                        {!! Form::text('param[admin_note_parent_id]',$data->admin_note_parent_id, ['class'=>'form-control','id'=>'admin_note_parent_id','maxlength'=>30]) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('admin_note_sort', '排序编号:', ['class'=>'col-sm-2 control-label']) !!}

                    <div class="col-sm-3">
                        {!! Form::text('param[admin_note_sort]',$data->admin_note_sort, ['class'=>'form-control','id'=>'admin_note_sort','maxlength'=>30]) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('admin_note_show', '是否导航显示:', ['class'=>'col-sm-2 control-label']) !!}

                    <div class="col-sm-3">
                        {!! Form::text('param[admin_note_show]',$data->admin_note_show, ['class'=>'form-control','id'=>'admin_note_show','maxlength'=>30]) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('admin_has_subset', '是否拥有子集:', ['class'=>'col-sm-2 control-label']) !!}

                    <div class="col-sm-3">
                        {!! Form::text('param[admin_has_subset]',$data->admin_has_subset, ['class'=>'form-control','id'=>'admin_has_subset','maxlength'=>30]) !!}
                    </div>
                </div>
                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <div class="col-sm-4 col-sm-offset-2">
                        {!! Form::submit('提交 ',['class'=>'btn btn-primary buttons']) !!}
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@stop

@section('script')
<!-- Image cropper -->
<script src="/inspinia/js/plugins/cropper/cropper.min.js"></script>

<script>
    $(function(){


        $('.buttons').click(function(){

            var admin_note_name = $('#admin_note_name').val();
            if(!admin_note_name){
                layer.tips('不能为空!', '#admin_note_name',{ tips: 3});
                return false;
            }

            var admin_note_zh_name = $('#admin_note_zh_name').val();
            if(!admin_note_zh_name){
                layer.tips('不能为空!', '#admin_note_zh_name',{ tips: 3});
                return false;
            }
            var admin_note_default = $('#admin_note_default').val();
            if(!admin_note_default){
                layer.tips('不能为空!', '#admin_note_default',{ tips: 3});
                return false;
            }
            var admin_box = $('#admin_box').val();
            if(!admin_box){
                layer.tips('不能为空!', '#admin_box',{ tips: 3});
                return false;
            }
            var admin_note_controller = $('#admin_note_controller').val();
            if(!admin_note_controller){
                layer.tips('不能为空!', '#admin_note_controller',{ tips: 3});
                return false;
            }
            var admin_note_action = $('#admin_note_action').val();
            if(!admin_note_action){
                layer.tips('不能为空!', '#admin_note_action',{ tips: 3});
                return false;
            }
            var admin_note_parent_id = $('#admin_note_parent_id').val();
            if(!admin_note_parent_id){
                layer.tips('不能为空!', '#admin_note_parent_id',{ tips: 3});
                return false;
            }
            var admin_note_sort = $('#admin_note_sort').val();
            if(!admin_note_sort){
                layer.tips('不能为空!', '#admin_note_sort',{ tips: 3});
                return false;
            }
            var admin_note_show = $('#admin_note_show').val();
            if(!admin_note_show){
                layer.tips('不能为空!', '#admin_note_show',{ tips: 3});
                return false;
            }
            var admin_has_subset = $('#admin_has_subset').val();
            if(!admin_has_subset){
                layer.tips('不能为空!', '#admin_has_subset',{ tips: 3});
                return false;
            }
            var $this = $(this);

            var data = $("form").serialize();
            var url = $('form').attr('action');

            $this.attr("disabled",true);
            $.ajax({
                url: url,// 跳转到 action
                data: data,
                type: 'post',
                cache: false,
                dataType: 'json',
                success: function (data) {
                    if (data['status'] == 1) {

                        layer.msg(data['msg'], { icon: 1});
                        location.replace('/AdminNote/index.html');

                    } else {
                        layer.msg(data['msg'], { icon: 2});
                        $this.attr("disabled",false);
                    }
                },
                error: function () {
                    $this.attr("disabled",false);
                }
            });

            return false;



        });
    });


</script>
@stop
