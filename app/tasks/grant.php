<?php

namespace Fuel\Tasks;

    ## Get auth name
    // \Auth::group()->get_name();

    ## Auth actions
    // Authorization : array('view', 'add', 'edit', 'delete', 'modify')
    ## Test actions right
    //if ( \Auth::has_access('admin.list[view]')) {
        //echo 'you have access ...';
    //}

    # Debug auth
    // $x = \Cache::get(\Config::get('ormauth.cache_prefix', 'auth').'.permissions.user_6');
    // \Debug::dump(
    //  $x,
    //  \Auth::has_access('admin.test'),
    //  \Auth::has_access('admin.failed'),
    //  \Auth::has_access('admin.test[delete]'),
    //  \Auth::has_access('admin.test[add]')
    // );


class Grant {

    protected static $but_actions = array('view', 'add', 'edit', 'delete', 'modify');

    public static function run($area = NULL, $permission = NULL, $desc = NULL) {

        if ($area != NULL && $permission != NULL && $desc != NULL) {

            $permission=\Model\Auth_Permission::forge(
                array(  'area' => $area,
                        'permission' => $permission,
                        'description' => $desc,
                        'actions' => static::$but_actions,
                )
            );
            $permission->save();

            echo 'Created permission correct.';

        } else {
            echo 'Need $area, $permission and $description';
        }

        echo "\n";

    }

    public function role($role_id = NULL, $perm_id = NULL, $access = NULL) {

        if ($role_id != NULL && $perm_id != NULL && $access != NULL) {

            $array_actions=explode(',',$access);
            $actions= [];
            foreach ($array_actions as $key) {
                array_push($actions, array_search($key, static::$but_actions));
            }

            $permission_role=\Model\Auth_Rolepermission::forge(array('role_id'=>$role_id,'perms_id'=>$perm_id,'actions'=>$actions));
            $permission_role->save();

            echo 'Role permission setup correct.';

        } else {
            echo "\n";
            echo 'Need values: [Role ID] [Permisssion ID] [Access="view,add,edit,delete,modify"]';
            echo "\n";
        }

        echo "\n";
    
    }

    public function group($group_id = NULL, $perm_id = NULL, $access = NULL) {

        if ($group_id != NULL && $perm_id != NULL && $access != NULL) {

            $array_actions=explode(',',$access);
            $actions= [];
            foreach ($array_actions as $key) {
                array_push($actions, array_search($key, static::$but_actions));
            }

            $permission_group=\Model\Auth_Grouppermission::forge(array('group_id'=>$group_id,'perms_id'=>$perm_id,'actions'=>$actions));
            $permission_group->save();

            echo 'Group permission setup correct.';

        } else {
            echo "\nSyntax is :\n";
            echo 'oil r grant:group [Group ID] [Permisssion ID] [Access="view,add,edit,delete,modify"]';
            echo "\n";
        }

        echo "\n";
    
    }

    public function flush() {

        // flush all the cached permissions
        \Cache::delete(\Config::get('ormauth.cache_prefix', 'auth').'.permissions');

        // flush all the cached groups
        \Cache::delete(\Config::get('ormauth.cache_prefix', 'auth').'.groups');

        // flush all the cached roles
        \Cache::delete(\Config::get('ormauth.cache_prefix', 'auth').'.roles');

        echo "Flushed all permissions.";
        echo "\n";
        
    }

    public function flush_all() {

        \Cache::delete_all(\Config::get('ormauth.cache_prefix', 'auth').'.permissions');

    }
/*
    public function test() {

        ## I create user tester in group 4

        $group = 4;
        $user = Auth::instance()->create_user('tester', '1234', 'tester@domain.com', $group, array('fullname' => 'Mister tester'));

        # I create permission

        $permission=\Model\Auth_Permission::forge(
            array(  'area' => 'admin',
                    'permission' => 'test',
                    'description' => 'testing admin in module admin and controller list',
                    'actions' => array('view', 'add', 'edit', 'delete', 'modify'),
            )
        );
        $permission->save();


        # I create a this permission for this group with only 'view' action

        $actions = array(1);

        $permission_group=\Model\Auth_Grouppermission::forge(array('group_id'=>$group,'perms_id'=>$permission->id,'actions'=>$actions));
        $permission_group->save();

    }
*/

}