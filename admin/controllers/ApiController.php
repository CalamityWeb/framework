<?php

namespace tframe\admin\controllers;

use tframe\common\models\User;
use tframe\core\Application;
use tframe\core\auth\AuthGroup;
use tframe\core\auth\AuthItem;
use tframe\core\Controller;
use tframe\core\exception\UnauthorizedException;

class ApiController extends Controller {

    private function checkLogged(): void {
        if(Application::isGuest()) {
            throw new UnauthorizedException();
        }
    }

    public function usersListUsers(): false|string {
        $this->checkLogged();
        $users = User::findMany();
        return json_encode($users);
    }

    public function routesManagementItemsListItems(): false|string {
        $this->checkLogged();
        $authItems = AuthItem::findMany();
        return json_encode($authItems);
    }

    public function routesManagementGroupsListGroups(): false|string {
        $this->checkLogged();
        $authGroups = AuthGroup::findMany();
        return json_encode($authGroups);
    }
}