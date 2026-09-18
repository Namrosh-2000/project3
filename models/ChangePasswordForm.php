<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * Password change form.
 */
class ChangePasswordForm extends Model
{
    public $currentPassword;
    public $newPassword;
    public $confirmPassword;

    private $_user;

    public function __construct($user = null, $config = [])
    {
        if ($user !== null) {
            $this->_user = $user;
        }
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['currentPassword', 'newPassword', 'confirmPassword'], 'required'],
            [['currentPassword'], 'validateCurrentPassword'],
            [['newPassword'], 'string', 'min' => 6],
            [['confirmPassword'], 'compare', 'compareAttribute' => 'newPassword', 'message' => 'Passwords do not match.'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'currentPassword' => 'Current Password',
            'newPassword' => 'New Password',
            'confirmPassword' => 'Confirm New Password',
        ];
    }

    public function validateCurrentPassword($attribute, $params)
    {
        if ($this->_user === null) {
            return;
        }
        if (!$this->_user->validatePassword($this->currentPassword)) {
            $this->addError($attribute, 'Current password is incorrect.');
        }
    }

    public function change()
    {
        if (!$this->validate() || $this->_user === null) {
            return false;
        }
        $this->_user->setPassword($this->newPassword);
        return $this->_user->save(false);
    }
}