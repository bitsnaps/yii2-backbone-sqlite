<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * SignupForm is the model behind the contact form.
 */
class SignupForm extends Model
{
    public $username;
    // public $email;
    public $password;
    public $verifyCode;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['username', /*'email',*/ 'password'], 'required'],
            // ['email', 'email'],
            ['verifyCode', 'captcha'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'username' => 'User Name',
            // 'email' => 'E-Mail',
            'verifyCode' => 'Verification Code',
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     * @param string $email the target email address
     * @return bool whether the model passes validation
     */
     public function signup()
     {

       if ($this->validate()) {
         $user = new User();
         $user->username = $this->username;
         // $user->email = $this->email;
         $user->setPassword($this->password);
         // $user->generateAuthKey(); // this is done in User->beforeSave() instead
         $user->generateAccessToken();
         $user->save(false);
         // $this->sendEmail($user);

         // the following three lines were added:
         // $auth = \Yii::$app->authManager;
         // $authorRole = $auth->getRole('author');
         // $auth->assign($authorRole, $user->getId());

         return $user;
       }
       return null;
     }

     /**
      * Sends an email with a link, for resetting the password.
      *
      * @return boolean whether the email was send
      */
     public function sendEmail()
     {
         /* @var $user User */
         $class = Yii::$app->getUser()->identityClass ? : 'app\models\User';
         $user = $class::findOne([
             'status' => User::STATUS_ACTIVE,
             'email' => $this->email,
         ]);

         if ($user) {
             if (!$this->isPasswordResetTokenValid($user->password_reset_token)) {
                 $user->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
             }

             if ($user->save()) {
                 return Yii::$app->mailer->compose([
                   'html' => 'passwordResetToken-html',
                   'text' => 'passwordResetToken-text'
                 ], [
                   'user' => $user
                 ])->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
                   ->setTo($this->email)
                   ->setSubject('Password reset for ' . Yii::$app->name)
                   ->send();
             }
         }

         return false;
     }

     /**
      * Finds out if password reset token is valid
      *
      * @param string $token password reset token
      * @return boolean
      */
     public static function isPasswordResetTokenValid($token)
     {
         if (empty($token)) {
             return false;
         }
         $expire = Yii::$app->params['user.passwordResetTokenExpire']?:(3600*24*30);
         $parts = explode('_', $token);
         $timestamp = (int) end($parts);
         return $timestamp + $expire >= time();
     }

}
