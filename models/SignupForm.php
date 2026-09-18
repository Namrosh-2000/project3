<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * Signup form — supports seeker, owner, and agent roles.
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $phone;
    public $password;
    public $role;

    public function rules()
    {
        return [
            [['username', 'email', 'password', 'role'], 'required'],
            [['username'], 'string', 'min' => 3, 'max' => 50],
            [['username'], 'match', 'pattern' => '/^[A-Za-z0-9_.-]+$/', 'message' => 'Username may only contain letters, numbers, dot, dash or underscore.'],
            [['email'], 'email'],
            [['email'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 20],
            [['password'], 'string', 'min' => 6],
            [['role'], 'in', 'range' => [User::ROLE_SEEKER, User::ROLE_OWNER, User::ROLE_AGENT]],
            [['username'], 'unique', 'targetClass' => User::class, 'targetAttribute' => 'username', 'message' => 'This username is already taken.'],
            [['email'], 'unique', 'targetClass' => User::class, 'targetAttribute' => 'email', 'message' => 'This email is already registered.'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Username',
            'email' => 'Email',
            'phone' => 'Phone',
            'password' => 'Password',
            'role' => 'I am a',
        ];
    }

    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->phone = $this->phone;
        $user->role = $this->role;
        $user->status = User::STATUS_ACTIVE;
        $user->setPassword($this->password);
        $user->generateAuthKey();

        return $user->save() ? $user : null;
    }
}