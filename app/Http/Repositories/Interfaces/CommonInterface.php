<?php
/**
 * User: fly
 * Date: 16-6-4
 * Time: 下午5:04
 */
namespace App\Http\Repositories\Interfaces;

/**
 * 规范每一个模型里面进行 处理全部数据 查找一个数据 删除数据 保存新增数据的规范
 * Interface CommonInterface
 *
 * @package App\Http\Repositories\Interfaces
 */
interface CommonInterface {

    public static function queryData($param,$limit);

    public static function findData($id);

    public static function delData($id);

    public static function saveData($id,$param);

}