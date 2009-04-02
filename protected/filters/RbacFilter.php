<?php
/**
 * This filter implements core RBAC access filtering
 *
 * @author oleksiy
 */
class RbacFilter extends COutputProcessor {

    public function filter($filterChain)
    {
        $this->preFilter($filterChain);
        return parent::filter($filterChain);
    }

    protected function preFilter($filterChain)
    {
        if(Yii::app()->user->isGuest){
            Yii::app()->user->loginRequired();
        }

        if(Yii::app()->rbac->checkAccess($filterChain->controller->id, $filterChain->action->id, Yii::app()->user->id)){
            Yii::trace("Access to ".$controller->id."/".$action->id." for username ".Yii::app()->user->name." granted with permission '".$p['title']."' [ID:".$p['id']."]");
            return true;
        }

        throw new CHttpException(401,Yii::t('yii','You are not authorized to perform this action.'));
    }

    public function processOutput($content)
    {
        echo $content;
    }
    
}
?>
