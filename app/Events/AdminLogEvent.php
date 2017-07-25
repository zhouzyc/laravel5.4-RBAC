<?php

namespace App\Events;

use App\Events\Event;
use App\Http\Controllers\Backstage\Controller;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Support\Facades\Input;

class AdminLogEvent extends Event
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($model)
    {


        if(!env('ADMIN_LOG','true')){
            return true;
        }

        $controllerModel = new Controller();
        $method = $controllerModel->getCurrentAction();


        $controller = \Route::current()->getName();

        if($model->adminLogTabel){
            $controller = $model->adminLogTabel;
        }

        $action = $method['method'];


        $adminLogData =  $model->adminLogData;
        $adminLogName =  $model->adminLogName;


        if(empty($adminLogData) || !$adminLogName ){
            if($model->adminLogMsg){
                $controllerModel->setEventAdminLog($controller,$model->adminLogMsg);
                return true;
            }else{
                return false;
            }
        }
;
        $str = '';
        if($action == 'del'){
            $str.=('删除了'.$adminLogData['admin_log_name'].':('.$model[$adminLogName].')');
            $controllerModel->setEventAdminLog($controller,$str);
            return true;
        }

        $param = Input::get('param');

        if(empty($param)){
            return false;
        }

        if(empty($model['original'])){

            $str.=('新增'.$adminLogData['admin_log_name'].':('.$model['attributes'][$adminLogName].')<br>');

            if($model->adminLogMsg){
                $str.= $model->adminLogMsg;
            }

            $controllerModel->setEventAdminLog($controller,$str);
            return true;
        }

        $data = $model->toArray();

        if(!$data){
            return false;
        }
        $messages = array_diff_assoc($model['original'], $model['attributes']);


        if($messages){
            foreach($messages as $key=>$val){
                if(isset($adminLogData[$key])){
                    if(is_array($val)){
                        if( !isset($param[$key])){
                            continue;
                        }
                        $str.=('修改了['.$model[$adminLogName].'] '.$adminLogData['admin_log_name'].$adminLogData[$key].'<br>');
                    }else{
                        if(is_array($adminLogData[$key])){
                            $name    = $adminLogData[$key]['name'];
                            $nameKey = $adminLogData[$key]['val'][$model['original'][$key]];
                            $paramKey= $adminLogData[$key]['val'][$model['attributes'][$key]];
                        }else{
                            $name = $adminLogData[$key];
                            $nameKey = $model['original'][$key];
                            $paramKey = $model['attributes'][$key];
                        }
                        $str.=('将['.$model[$adminLogName].'] '.$adminLogData['admin_log_name'].$name.'('.$nameKey.')'.'修改为:('.$paramKey.')<br>');
                    }
                }
            }
        }
        if($model->adminLogMsg){
            $str.= $model->adminLogMsg;
        }

        if(!$str){
             return false;
        }

        $controllerModel->setEventAdminLog($controller,$str);
        return true;
    }


    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {

        return [];
    }
}
