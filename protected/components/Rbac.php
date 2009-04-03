<?php
/**
 * Description of Rbac
 *
 * @author oleksiy
 */
class Rbac {

    public function init()
    {
        
    }

    /**
     *
     * @param string $controller controller.id
     * @param string $action action.id
     * @param numeric $user user ID
     * @param array $fakeGET fake _GET array for evaluating businness rules
     * @return boolean true if user has access, false otherwise
     */
    public function checkAccess($controller, $action, $user = null, array $fakeGET = null)
    {
        $allow = false;
        
        if(!$user){
            $user = Yii::app()->user->id;
        }

        $sql = 'SELECT DISTINCT p.* FROM Permission p, User u, Role r, User_has_Role ur, Role_has_Permission rp
                WHERE p.id=rp.PermissionId
                    AND r.id=rp.RoleId
                    AND ur.RoleId=r.id
                    AND ur.UserId=u.id
                    AND u.id=:uid
                    AND p.controller=:controller
                    AND p.action=:action
                ORDER BY p.bizrule ASC';

        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":uid", $user, PDO::PARAM_INT);
        $command->bindParam(":controller", $controller, PDO::PARAM_STR);
        $command->bindParam(":action", $action, PDO::PARAM_STR);
        $permission = $command->queryAll();


        if($fakeGET){ // lets fake GET request params for evaluating business rule
            $save_GET = $_GET;
            $_GET = $fakeGET;
        }

        if(!empty($permission)){
            foreach($permission as $p){
                if(empty($p['bizrule']) || @eval($p['bizrule'])){
                    $allow = true;
                    break;
                }
            }
        }

        if($fakeGET){ // restore original _GET
            $_GET = $save_GET;
        }

        return $allow;
    }

    /**
     *
     * @param mixed $searchCriteria can be a string or an array with RBAC search criterias
     * @param numeric $user user.id
     * @return boolean true if user satisfies rbac criteria $searchCriteria
     */
    public function checkAccessEx($searchCriteria, $user = null)
    {
        $from = array();
        $where = array();

        if(!$user){
            $user = Yii::app()->user->id;
        }

        $from[] = 'User u';
        $where[] = 'u.id='.intval($user);
        $this->parseSearchCriteria($searchCriteria, &$from, &$where);

        $sql = 'SELECT u.id FROM '.join($from, ', ').' WHERE '.join($where, ' AND ');
        $access = Yii::app()->db->createCommand($sql)->queryScalar();
        return(!empty($access));
    }

    private function parseSearchCriteria($conditions, &$from, &$where)
    {
        if(!is_array($conditions)){
            $conditions = array($conditions);
        }
        foreach($conditions as $c){
            $c .= ',';
            if(eregi('(User|Role|Permission)=', $c, $regs)){
                $talias = $regs[1]{0};
                $from[] = $regs[1].' '.$talias;
            }
            $or = array();
            if(preg_match_all('/(\w+):(.*?),/', $c, $regs)){
                for($i=0; $i<count($regs[0]); $i++){
                    if(is_numeric($regs[2][$i])){
                        $or[] = $talias.'.'.$regs[1][$i].'='.$regs[2][$i];
                    } else {
                        $value = ereg_replace('[\'\"]', '', $regs[2][$i]);
                        $or[] = $talias.'.'.$regs[1][$i].' LIKE \''.$value.'\'';
                    }
                }
            }
            if(count($or)){
                $where[] = '('.join($or, ' OR ').')';
            }
        }

        $addUserHasRole = false;
        $addRoleHasPermission = false;
        if(ereg('User', join($from, ' ')) && ereg('Oermission', join($from, ' '))){
            $from[] = 'Role r';
            $addUserHasRole = true;
            $addRoleHasPermission = true;
        } else {
            if(ereg('User', join($from, ' ')) && ereg('Role', join($from, ' '))){
                $addUserHasRole = true;
            }
            if(ereg('Permission', join($from, ' ')) && ereg('Role', join($from, ' '))){
                $addRoleHasPermission = true;
            }
        }
        if($addRoleHasPermission){
            $from[] = 'Role_has_Permission rp';
            $where[] = 'r.id=rp.RoleId';
            $where[] = 'p.id=rp.PermissionId';
        }
        if($addUserHasRole){
            $from[] = 'User_has_Role ur';
            $where[] = 'u.id=ur.UserId';
            $where[] = 'r.id=ur.RoleId';
        }

        $from = array_unique($from);
        $where = array_unique($where);
    }

}