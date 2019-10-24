<?php
namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\api\validate\IDCollection;
use app\api\validate\IDMustBeInt;
use app\libs\exception\ThemeException;

class Theme extends BaseController
{
    /**
     * @url     /theme?ids=:id1,id2,id3...
     * @return  array of theme
     * @throws  ThemeException
     */
    public function getSimpleList($ids = '')
    {
    	(new IDCollection())->goCheck();
        $idsArr = explode(',', $ids);
    	$themes = Model('Theme')->getThemeList($idsArr);
    	if($themes->isEmpty()){
    		throw new ThemeException();
    	}
    	return $themes;   
    }

    public function getComplexOne($id){
        (new IDMustBeInt())->goCheck();
        $theme = Model('Theme')->getThemeById($id);
        if(!$theme){
            throw new ThemeException();
        }
        return $theme->hidden(['products.summary']);
    }
}
