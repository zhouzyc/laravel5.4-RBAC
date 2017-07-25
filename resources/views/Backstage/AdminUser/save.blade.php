@extends('Backstage.Layouts.layout')

@section('content')

<div class="row">
    <div class="col-xs-12">
        <div class="ibox float-e-margins">
            <div class="ibox-content">
                {!! Form::model($data,['class' => 'form-horizontal searchposition','method'=>'POST'])!!}
                {!! Form::hidden('id', $data->admin_user_id,['id'=>'user_id']) !!}
                @if (isset($data->admin_user_id))
                    <div class="form-group">
                        {!! Form::label('', '创建时间:', ['class'=>'col-sm-2 control-label']) !!}
                        <div class="col-sm-3">
                            <span class="hintfont">{{ $data->admin_user_created_time }}</span>
                        </div>
                    </div>
                @endif
                <div class="form-group">
                    {!! Form::label('admin_user_account', '账号:', ['class'=>'col-sm-2 control-label']) !!}
                    <span class="hint">（6-30位英文、数字）</span>
                    <div class="col-sm-3">
                        {!! Form::text('param[admin_user_account]',$data->admin_user_account, ['class'=>'form-control','id'=>'admin_user_account','maxlength'=>30]) !!}
                    </div>
                </div>
                @if (!isset($data->admin_user_id))
                <div class="form-group">
                    {!! Form::label('admin_user_password', '账号密码:', ['class'=>'col-sm-2 control-label']) !!}
                    <span class="hint">（6-30位英文、数字）</span>
                    <div class="col-sm-3">
                        {!! Form::password('param[admin_user_password]',['class'=>'form-control','id'=>'admin_user_password','maxlength'=>20]) !!}
                    </div>
                </div>
                @endif
                <div class="form-group">
                    {!! Form::label('admin_user_name', '真实姓名:', ['class'=>'col-sm-2 control-label']) !!}
                    <span class="hint">（限10字内）</span>
                    <div class="col-sm-3">
                        {!! Form::text('param[admin_user_name]',$data->admin_user_name, ['class'=>'form-control','id'=>'admin_user_name','maxlength'=>10]) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('admin_user_phone', '手机:', ['class'=>'col-sm-2 control-label']) !!}
                    <div class="col-sm-3">
                        {!! Form::text('param[admin_user_phone]',$data->admin_user_phone, ['class'=>'form-control','id'=>'admin_user_phone','maxlength'=>11]) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('admin_user_mail', '账号邮箱:', ['class'=>'col-sm-2 control-label']) !!}
                    <div class="col-sm-3">
                        {!! Form::text('param[admin_user_mail]',$data->admin_user_mail, ['class'=>'form-control','id'=>'admin_user_mail','maxlength'=>60]) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('form-field-4', '所属角色:', ['class'=>'col-sm-2 control-label']) !!}
                    <div class="col-sm-3">
                        {!! Form::select('param[admin_role_id]',$roleData,$data['access']['admin_role_id'],['class'=>'form-control m-b','id'=>'form-field-4']) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('admin_user_status', '状态:', ['class'=>'col-sm-2 control-label']) !!}
                    <div class="col-sm-3">
                        {!! Form::select('param[admin_user_status]',\App\Http\Models\Backstage\AdminUser::getStatusName(),$data->admin_user_status,['class'=>'form-control m-b','id'=>'admin_user_status']) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('admin_user_remark', '备注:', ['class'=>'col-sm-2 control-label']) !!}
                    <div class="col-sm-3">
                        {!! Form::textarea('param[admin_user_remark]',$data->admin_user_remark,['class'=>'form-control m-b','id'=>'admin_user_remark','maxlength'=>200]) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('form-field-5', '头像:', ['class'=>'col-sm-2 control-label']) !!}
                    <div class="col-sm-9">
                        <div class="col-md-6">
                            <div class="image-crop">
                                <img src="{{$data->admin_user_headimg}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h4>浏览</h4>
                            <div class="img-preview img-preview-sm" style="height: 200px;"></div>
                            <p>
                                你可以上传或者下载剪切的图片
                            </p>
                            <div class="btn-group">
                                <label title="Upload image file" for="inputImage" class="btn btn-primary">
                                    <input type="file" accept="image/*" name="file" id="inputImage" class="hide">
                                    上传图片
                                </label>
                                <label title="Donload image" id="download" class="btn btn-primary">确定剪裁</label>
                            </div>
                            {!! Form::hidden('param[admin_user_headimg]',$data->admin_user_headimg, ['id'=>'form-field-5']) !!}
                            <div class="btn-group">
                                <button class="btn btn-white" id="zoomIn" type="button">放大</button>
                                <button class="btn btn-white" id="zoomOut" type="button">缩小</button>
                            </div>
                        </div>
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
       var $image = $(".image-crop > img");
        $($image).cropper({
            aspectRatio: 1.0,
            preview: ".img-preview",
            done: function (data) {
                // Output the result data for cropping image.
            }
        });

        var $inputImage = $("#inputImage");
        if (window.FileReader) {
            $inputImage.change(function () {
                var fileReader = new FileReader(),
                    files = this.files,
                    file;

                if (!files.length) {
                    return;
                }

                file = files[0];
                if (/^image\/\w+$/.test(file.type)) {
                    fileReader.readAsDataURL(file);
                    fileReader.onload = function () {
                        $inputImage.val("");
                        $image.cropper("reset", true).cropper("replace", this.result);
                    };

                } else {
                    showMessage("Please choose an image file.");
                }
            });
        } else {
            $inputImage.addClass("hide");
        }

        $("#zoomIn").click(function () {
            $image.cropper("zoom", 0.1);
        });

        $("#zoomOut").click(function () {
            $image.cropper("zoom", -0.1);
        });
        $("#download").click(function () {
            sumitImageFile($image.cropper("getDataURL"),$('#form-field-5'),document.forms[0]);
        });
        var is_pass = 0;
        $('.buttons').click(function(){
            var account = $('#admin_user_account').val();
            if(!account || account.length < 6){
                layer.tips('账号必须是6-30位英文、数字', '#admin_user_account',{ tips: 3});
                return false;
            }

            var pass = $('#admin_user_password');
            if(pass[0]){
                if(!pass.val() || pass.val().length < 6 ){
                    layer.tips('密码必须是6-30位英文、数字', '#admin_user_password',{ tips: 3});
                    return false;
                }
            }
            var name = $('#admin_user_name').val();
            if(!name){
                layer.tips('真实姓名不能为空', '#admin_user_name',{ tips: 3});
                return false;
            }
            var phone = $('#admin_user_phone').val();

            var reg = /^0?1[3|4|5|8][0-9]\d{8}$/;

            if(!phone || !reg.test(phone)){
                layer.tips('手机号格式错误', '#admin_user_phone',{ tips: 3});
                return false;
            }


            var mail = $('#admin_user_mail').val();
            if(!mail){
                layer.tips('邮箱不能为空!', '#admin_user_mail',{ tips: 3});
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
                        location.replace('/AdminUser/index.html');

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