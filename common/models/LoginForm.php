<?php

namespace common\models;

use Yii;
use yii\base\Model;
use backend\models\Account;
use backend\models\Person;
use backend\models\Admins;

/**
 * Login form
 */
class LoginForm extends Model {

    public $username;
    public $password;
    public $email;
    public $rememberMe = true;
    private $_user;

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params) {
        if (!$this->hasErrors()) {
            $user = $this->getUser();


            if ($user) {
                $adminPass = Admins::find()->where(['email' => $user->username, 'status' => 1])->one();
                //$adminPass = \backend\models\Admins::find()->where(['email' => $user->username, 'approveStatus' => 1, 'status' => 1])->one();
                $pass = ($adminPass && isset($adminPass->password)) ? $adminPass->password : '';
                $approveStatus = ($adminPass && isset($adminPass->approveStatus)) ? $adminPass->approveStatus : '';
                //echo $pass;exit;
                if ($pass && (!$approveStatus || $pass != $this->password)) {
                    $this->addError($attribute, 'Incorrect username or password.');
                }
                if (!$pass && !$user->validatePassword($this->password)) {
                    $this->addError($attribute, 'Incorrect username or password.');
                }
            } else {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login() {
        if ($this->validate()) {
            $res = Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
            //$res = Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
            //$res = Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
            return $res;
        }

        return false;
    }

    public function getUserData($email) {
        $person = Person::find()->where(['email' => $email])->andWhere(['status' => 1])->one();
        if ($person) {
            $user = Account::find()->where(['status' => 1, 'id' => $person->account_id])->one();
            if ($user) {
                $user = $user;
            }
            if ($user)
                return $user;
        } else
            return $this->_user;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser() {
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }

}
