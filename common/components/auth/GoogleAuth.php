<?php

namespace calamity\common\components\auth;

use calamity\common\helpers\CoreHelper;
use calamity\common\models\core\Calamity;
use calamity\common\models\core\exception\InvalidConfigException;
use calamity\common\models\Roles;
use calamity\common\models\Users;
use Google\Client;
use Google\Service;
use Google\Service\Oauth2\Userinfo;

class GoogleAuth {
    private Client $client;

    public function __construct() {
        $this->client = new Client();

        if (!file_exists(CoreHelper::getAlias('@common') . '/config/client_secret.json')) {
            throw new InvalidConfigException(Calamity::t('general', 'Google client_secret.json file does not exist!'));
        }

        $this->client->setAuthConfig(CoreHelper::getAlias('@common') . '/config/client_secret.json');
        $this->client->addScope('https://www.googleapis.com/auth/userinfo.email');
        $this->client->addScope('https://www.googleapis.com/auth/userinfo.profile');
        $this->client->setRedirectUri(Calamity::$URL['@admin'] . '/auth/google-auth');
        $this->client->setAccessType('offline');
    }

    public function getRedirectUrl(): string {
        return $this->client->createAuthUrl();
    }

    public function getAccessToken($accessCode): array|string {
        return $this->client->fetchAccessTokenWithAuthCode($accessCode);
    }

    public function provideAccessToken(array|string $token): void {
        $this->client->setAccessToken($token);
    }

    public function getClient(): Client {
        return $this->client;
    }

    public static function registerGoogleUser(Userinfo $userinfo): Users {
        $user = new Users();
        $user->email = $userinfo->getEmail();
        $user->firstName = $userinfo->getGivenName();
        $user->lastName = $userinfo->getFamilyName();
        $user->auth_provider = 'google';
        $user->email_confirmed = false;
        $user->password = null;
        $user->save();

        $userRole = new UserRoles();
        $userRole->userId = Users::findOne(['email' => $userinfo->getEmail()])->id;
        $userRole->roleId = Roles::findOne(['roleName' => 'Visitor'])->id;
        $userRole->save();

        return Users::findOne(['email' => $userinfo->getEmail()]);
    }
}