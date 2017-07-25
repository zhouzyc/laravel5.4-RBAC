@if (!empty($adminLog))
    <div class="ibox float-e-margins" style="margin-bottom: 50px;">
        <div class="ibox-title">
            <h5>操作日志</h5>
            <div class="ibox-tools">
                <a class="collapse-link">
                    <i class="fa fa-chevron-up"></i>
                </a>
                <a class="close-link">
                    <i class="fa fa-times"></i>
                </a>
            </div>
        </div>
        <div class="ibox-content" >
                <div class="row" style="border-top: none">
                    <div class="col-xs-12">
                        <div class="ibox float-e-margins">
                            <div class="ibox-content">

                                <div class="table-responsive">
                                    <table id="sample-table-1" class="table table-bordered">
                                        <thead>
                                        <tr >
                                            <th style="text-align: center">操作人</th>
                                            <th style="text-align: center">操作</th>
                                            <th style="text-align: center">时间</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($adminLog as $val)
                                            <tr style="text-align: center">
                                                <td>{!! $val['name'] !!}</td>
                                                <td>{!! $val['msg'] !!}</td>
                                                <td><?php  echo ($val['date']['date'])  ?></td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                {!! $adminPage !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    <div style="height: 80px;">    </div>
@endif
