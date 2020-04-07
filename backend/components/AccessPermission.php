<?php
 namespace backend\components;

 use backend\models\AccountBackend;
 use backend\models\AuthItemChild;
use backend\models\AuthItem;
class AccessPermission extends \yii\filters\AccessRule {

    /**
     * @inheritdoc
     */
    protected function matchRole($user)
    {
      if($user->identity){
        $modelUserPermissions = AuthItemChild::find()->where(['parent'=>$user->identity->role])->all();
        foreach ($modelUserPermissions as $modelUserPermission) {
          if (empty($this->permissions)) {
              return true;
          }
          foreach ($this->permissions as $permission) {
            if ($permission == '?') {
                if ($user->getIsGuest()) {
                    return true;
                }
            } elseif (!$user->getIsGuest() && $permission == $modelUserPermission->child) {
                return true;
            }
          }
        }
      }
      return false;
    }
}
