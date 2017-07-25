@extends('Backstage.Layouts.layout')

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="ibox float-e-margins">
            <div class="ibox-content">
                {!! Form::model($data,['class'=> 'form-horizontal searchposition','method'=>'POST'])!!}
                {!! Form::hidden('id', $data->admin_role_id) !!}
                @if (isset($data->admin_role_id))
                    <div class="form-group">
                        {!! Form::label('', '创建时间:', ['class'=>'col-sm-2 control-label']) !!}
                        <div class="col-sm-3">
                            <span class="hintfont">{{ $data->admin_role_created_time }}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('', '成员数:', ['class'=>'col-sm-2 control-label']) !!}
                        <div class="col-sm-3">
                            <span class="hintfont">{{ $data->admin_role_number }}</span>
                        </div>
                    </div>
                @endif
                <div class="form-group">
                    {!! Form::label('form-field-1', '角色名称:', ['class'=>'col-sm-2 control-label']) !!}
                    <span class="hint">（限10个字内）</span>
                    <div class="col-sm-3">
                        {!! Form::text('param[admin_role_name]',$data->admin_role_name, ['class'=>'form-control','id'=>'form-field-1','maxlength'=>10,'check'=>'admin_role_name']) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('admin_role_status', '角色状态:', ['class'=>'col-sm-2 control-label']) !!}
                    <div class="col-sm-3">
                        {!! Form::select('param[admin_role_status]',\App\Http\Models\Backstage\AdminRole::getStatusName(),$data->admin_role_status,['class'=>'form-control m-b','id'=>'admin_role_status']) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('form-field-1', '角色权限:', ['class'=>'col-sm-2 control-label']) !!}

                    <div class="col-sm-10" style="padding-left:0;">
                        @foreach ($noteData as $val)
                            <div class="col-sm-10">
                                <div class="hr-line-dashed" style="margin-bottom: 10px;!important;margin-top: 10px!important;"></div>
                                @if (($val['admin_note_default'] == 1 && $data->admin_role_id < 1))
                                    <?php $ischeck = true; ?>
                                @else
                                    <?php $ischeck = false; ?>
                                @endif
                                @if (!empty($data['roleUser']))
                                    @foreach ($data['roleUser'] as $roleUser)
                                        @if ($roleUser['admin_note_id'] == $val['admin_note_id'])
                                            <?php $ischeck = true; ?>
                                        @endif
                                    @endforeach
                                @endif
                                <label class="checkbox-inline i-checks">
                                    {!! Form::checkbox('param[role_user][]', $val['admin_note_id'], $ischeck) !!} {!! $val['admin_note_zh_name'] !!}
                                </label>

                                @if (!empty($val['children']))
                                    @foreach ($val['children'] as $children)
                                        @if (($children['admin_note_default'] == 1 && $data->admin_role_id < 1))
                                            <?php $ischeck = true; ?>
                                        @else
                                            <?php $ischeck = false; ?>
                                        @endif
                                        @if (!empty($data['roleUser']))
                                            @foreach ($data['roleUser'] as $roleUser)
                                                @if ($roleUser['admin_note_id'] == $children['admin_note_id'])
                                                <?php $ischeck = true; ?>
                                                @endif
                                            @endforeach
                                        @endif
                                            @if (($children['admin_box'] == 1))
                                                <div class="hr-line-dashed" style="margin-bottom: 10px;!important;margin-top: 10px!important;"></div>
                                            @endif
                                        <label class="checkbox-inline i-checks">
                                            {!! Form::checkbox('param[role_user][]', $children['admin_note_id'], $ischeck) !!} {!! $children['admin_note_zh_name'] !!}
                                        </label>

                                    @endforeach
                                @endif
                            </div>
                        @endforeach
                    </div>

            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <div class="col-sm-4 col-sm-offset-2">
                    {!! Form::submit('提交',['class'=>'btn btn-primary buttons']) !!}
                </div>
            </div>
            {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@stop
@section('script')
<script src="/inspinia/js/plugins/iCheck/icheck.min.js"></script>
<script>
    $(function(){
        $('.buttons').click(function(){
            if(!$('#form-field-1').val()){
                layer.tips('名称不能为空', '#form-field-1',{ tips: 3});
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
                        location.replace('/AdminRole/index.html');

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
        $('.i-checks').iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green'
        });
    });
</script>
@stop
