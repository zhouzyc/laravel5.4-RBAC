<?php


/**
 * 设置Session
 * Web:2.0 版本
 * User: fly
 * @param $key
 * @param $val
 */
function setSession($key,$val){
    if(!isset($_SESSION)){
        Session_Start();
    }
    if(!$val){
        unset ($_SESSION[$key]);
    }else{
        $_SESSION[$key] = $val;
    }
}

/**
 * 得到Session
 * Web:2.0 版本
 * User: fly
 * @param $key
 *
 * @return null
 */
function getSession($key){
    if(!isset($_SESSION)){
        Session_Start();
    }
    if(!isset($_SESSION[$key])){
        return null;
    }
    return $_SESSION[$key];
}

/**
 * 输出成功数据
 * Web:2.0 版本
 * User: fly
 * @param array  $data
 * @param int    $status
 * @param string $msg
 *
 * @return \Illuminate\Http\JsonResponse
 */
function echoOk($data = array(),$status = 1,$msg = '请求成功'){
    $data = array(
        'status'=>$status,
        'data'=>$data,
        'msg'=>$msg,
        'code'=>0,
    );
    return response()->json($data);
}

/**
 * 输出错误数据
 * Web:2.0 版本
 * User: fly
 * @param string $msg
 * @param string $code
 * @param int    $status
 *
 * @return \Illuminate\Http\JsonResponse
 */
function echoErr($msg = '请求失败',$code = '100001',$status = 0,$title = '提示信息',$statusCode = 200){
    $data = array(
        'status'=>$status,
        'data'=>array(),
        'msg'=>$msg,
        'code'=>$code,
        'title'=>$title,
    );
    return response()->json($data,$statusCode);
}




/**
 * 设置分页页码
 * Web:2.0 版本
 * User: fly
 * @param $page
 *
 * @return int
 */
function setPage($page,$limit){
    if(!$page){
        $page = 1;
    }

    $skip = ($page-1)*$limit;

    return $skip;
}
/**
 * 设置json分页输出
 * Web:2.0 版本
 * User: fly
 * @param $data
 * @param $count
 * @param $page
 * @param $limit
 *
 * @return array
 */
function setJsonPageData($data){

    $returnData['total'] = $data->total();
    $returnData['last_page'] = $data->lastPage();
    $returnData['per_page'] = $data->perPage();
    $returnData['current_page'] = $data->currentPage();
    $returnData['next_page_url'] = $data->nextPageUrl();
    $returnData['prev_page_url'] = $data->previousPageUrl();

    return $returnData;
}

/**
 *  暂时没用
 * 分页处理url上多余参数
 * Web:2.0 版本
 * User: fly
 * @return array
 */
function setPageParam(){
    $url = Request::getRequestUri();
    $urlData = parse_url($url);
    if(!isset($urlData['query'])){
        return array();
    }
    parse_str($urlData['query'],$param);
    unset($param['page']);
    return $param;
}

/**
 * 判断是否存在
 * Web:2.0 版本
 * User: fly
 * @param $param
 *
 * @return string
 */
function issetParam($param,$str){
    return isset($param[$str])?$param[$str]:'';
}






/**
 * 分页函数
 * Web:2.0 版本
 * User: fly
 * @param     $page //页数
 * @param int $count //总数量
 * @param int $limit  //每页多少条数据
 *
 * @return string
 */
function getPage($page,$count = 0,$limit = 20,$controller = '',$class = 'adminlog'){

    $str = '';
    $countPage = ceil($count/$limit);

    if($page == ''){
        $page = 1;
    }
    if($countPage>1){
        $str = '<div style="text-align: center"> <ul class="pagination '.$class.'">';
        if($page > 1){
//            $url = setPage(1);
            $str .= "<li data-page='1' data-name='$controller' ><a>查看首页</a></li> ";
//            $url = setPage(($page-1));
//            $str .= "<li>上一页</li> ";
        }
        //如果当前页小于3
        if($page < 3){
            $number = 1 ;
            $maxNumber = 5 ;
            if($countPage < 5){
                $maxNumber = $countPage ;
            }
            for($i = $number;$i<=$maxNumber;$i++){
//                $url = setPage($i);
                if($i == $page){
                    $str .= "<li data-page='$i'  data-name='$controller' class='active'><a>$i</a></li> ";
                }else{
                    $str .= "<li data-page='$i'  data-name='$controller' ><a>$i</a></li> ";
                }
            }
        }
        //如果当前页大于3
        if($page >= 3){
            $lastPage = 0;
            if(ceil($countPage - $page) > 0 ){
                $lastPage = $countPage - $page;
            }
            if($lastPage >= 3){
                $lastPage = 2;
            }
            for($i = ($page-2);$i<=($page+$lastPage);$i++){
//                $url = setPage($i);
                if($i == $page){
                    $str .= "<li data-page='$i'  data-name='$controller' class='active'><a>$i</a></li> ";
                }else{
                    $str .= "<li data-page='$i'  data-name='$controller' ><a>$i</a></li> ";
                }
            }
        }
        if($countPage > $page ){
//            $url = setPage($page+1);
//            $str .= "<li>下一页</li> ";
//            $url = setPage($countPage);
            $str .= "<li data-page='$countPage'  data-name='$controller' ><a>查看尾页</a></li> ";
        }
        $str .= '</ul> </div>';
    }
    return $str;
}

/**
 * 得到分页的url
 * Web:1.0 版本
 * User: fly
 * @param $page
 */
function setPageUrl($page){
//    $url = ('http://'.$_SERVER['HTTP_HOST'].__SELF__);
    $url = (__SELF__);
    $urlData = explode('?',$url);
    if($urlData[1] == ''){
        $urlData[1] = "page=$page";
    }
    $paramData = explode('&',$urlData[1]);
    $endParam = array();
    if(!empty($paramData)){
        $isPage = false;
        foreach($paramData as $val){
            $param = explode('=',$val);
            if($param[0] == 'page'){
                $isPage = true;
                $param[1] = $page;
            }
            $endParam[] = implode('=',$param);
        }
        if(!$isPage){
            $endParam[] =  "page=$page";
        }
    }
    $endurl = implode('&',$endParam);
    if($endurl != ''){
        $urlData[1] = $endurl;
    }
    $newUrl = implode('?',$urlData);
    return $newUrl;
}

function curlHttp($uri,$data,$type = 'GET')
{


    $ch = curl_init ();

    $fields_string = http_build_query ($data);

    if($type == 'GET'){
        $uri = $uri.'?'.$fields_string;

    }else{
        $headers = array(
            'Content-Type'=> 'text/html'
        );
        curl_setopt ( $ch, CURLOPT_POST, 1 );
        curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields_string );
    }

    curl_setopt ( $ch, CURLOPT_URL, $uri );
    curl_setopt ( $ch, CURLOPT_HEADER, 0 );
    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
    curl_setopt ( $ch, CURLOPT_TIMEOUT, 20);
    curl_setopt ( $ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
    $return = curl_exec ( $ch );
    curl_close ( $ch );

    return json_decode($return,true);
}