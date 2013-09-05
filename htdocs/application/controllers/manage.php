<?php

namespace App\Controllers;

use Scabbia\Extensions\Mvc\Controller;
use Scabbia\Extensions\Http\Http;
use Scabbia\Extensions\I18n\I18n;
use Scabbia\Extensions\Fb\Fb;
use Scabbia\Extensions\Session\Session;
use Scabbia\Extensions\Validation\Validation;
use Scabbia\Request;

/**
 * @ignore
 */
class manage extends Controller
{
    /**
     * @ignore
     */
    public function index()
    {
        // Auth::checkRedirect('user');

        $this->load('App\\Models\\homeModel');

        $tWelcomeText = $this->homeModel->getWelcomeText();
        $this->set('welcomeText', $tWelcomeText);
        
        $this->view();
    }

    /**
     * @ignore
     */
    public function users($uSubpage = 'index')
    {
        // Auth::checkRedirect('user');

        if ($uSubpage === 'index') {
            return $this->users_index();
        } elseif ($uSubpage === 'add') {
            return $this->users_add();
        } elseif ($uSubpage === 'edit') {
            return $this->users_edit();
        } elseif ($uSubpage === 'remove') {
            return $this->users_remove();
        }

        return false;
    }

    /**
     * @ignore
     */
    public function groups($uSubpage = 'index', $id = 0)
    {
        // Auth::checkRedirect('user');

        if ($uSubpage === 'index') {
            return $this->groups_index($id);
        } elseif ($uSubpage === 'add') {
            return $this->groups_add();
        } elseif ($uSubpage === 'edit') {
            return $this->groups_edit($id);
        } elseif ($uSubpage === 'remove') {
            return $this->groups_remove($id);
        }

        return false;
    }


    /**
     * @ignore
     */
    public function roles($uSubpage = 'index', $id = 0)
    {
        // Auth::checkRedirect('user');

        if ($uSubpage === 'index') {
            return $this->roles_index($id);
        } elseif ($uSubpage === 'add') {
            return $this->roles_add();
        } elseif ($uSubpage === 'edit') {
            return $this->roles_edit($id);
        } elseif ($uSubpage === 'remove') {
            return $this->roles_remove($id);
        }

        return false;
    }

    /**
     * @ignore
     */
    private function users_index()
    {
        $this->load('App\\Models\\userModel');

        $tUsers = $this->userModel->getUsers();
        $this->set('users', $tUsers);

        $this->view('manage/users/index.cshtml');
    }

    /**
     * @ignore
     */
    private function groups_index($uPage = 1)
    {
        $tPageSize = 25;

        $tPage = $uPage - 1;
        if ($tPage < 0) {
            $tPage = 0;
        }

        $this->load('App\\Models\\groupModel');

        $this->set('data', $this->groupModel->getGroupsWithPaging($tPage * $tPageSize, $tPageSize));
        $this->set('dataCount', $this->groupModel->getGroupsCount());

        $this->set('pageSize', $tPageSize);
        $this->set('page', $tPage);

        $this->view('manage/groups/index.cshtml');
    }

    /**
     * @ignore
     */
    private function groups_add()
    {
        if (Request::$method === 'post') {
            $tData = array(
                'name' => Request::post('name', null, null)
            );

            Validation::addRule('name')->isRequired()->errorMessage(I18n::_('Name field is required.'));

            if (!Validation::validate($tData)) {
                Session::set(
                    'alert',
                    array(
                        'error',
                        implode('<br />', Validation::getErrorMessages(true))
                    )
                );
            } else {
                $this->load('App\\Models\\groupModel');

                $tId = $this->groupModel->insert($tData);

                Session::set(
                    'alert',
                    array(
                        'success',
                        'Record added.'
                    )
                );

                // redirect to newly created
                Http::redirect('manage/groups/edit/' . $tId, true);
                return;
            }
        }

        $this->view('manage/groups/add.cshtml');
    }

    /**
     * @ignore
     */
    private function groups_edit($uId)
    {
        $this->load('App\\Models\\groupModel');

        $tOriginalData = $this->groupModel->get($uId);
        if ($tOriginalData === false) {
            return false;
        }

        if (Request::$method === 'post') {
            $tData = array(
                'name' => Request::post('name', null, null)
            );

            Validation::addRule('name')->isRequired()->errorMessage(I18n::_('Name field is required.'));

            if (!Validation::validate($tData)) {
                Session::set(
                    'alert',
                    array(
                        'error',
                        implode('<br />', Validation::getErrorMessages(true))
                    )
                );
            } else {
                $this->groupModel->update(
                    $uId,
                    $tData
                );

                Session::set(
                    'alert',
                    array(
                        'success',
                        'Record updated.'
                    )
                );
            }
        } else {
            $tData = $tOriginalData;
        }

        $this->set('id', $uId);
        $this->set('data', $tData);

        $this->view('manage/groups/edit.cshtml');
    }


    /**
     * @ignore
     */
    private function groups_remove($uId)
    {
        $this->load('App\\Models\\groupModel');

        $tOriginalData = $this->groupModel->get($uId);
        if ($tOriginalData === false) {
            return false;
        }

        $this->groupModel->delete(
            $uId
        );

        Session::set(
            'alert',
            array(
                'success',
                'Record removed.'
            )
        );

        // redirect to list
        Http::redirect('manage/groups', true);
        return;
    }

    /**
     * @ignore
     */
    private function roles_index($uPage = 1)
    {
        $tPageSize = 25;

        $tPage = $uPage - 1;
        if ($tPage < 0) {
            $tPage = 0;
        }

        $this->load('App\\Models\\roleModel');

        $this->set('data', $this->roleModel->getRolesWithPaging($tPage * $tPageSize, $tPageSize));
        $this->set('dataCount', $this->roleModel->getRolesCount());

        $this->set('pageSize', $tPageSize);
        $this->set('page', $tPage);

        $this->view('manage/roles/index.cshtml');
    }

    /**
     * @ignore
     */
    private function roles_add()
    {
        if (Request::$method === 'post') {
            $tData = array(
                'name' => Request::post('name', null, null),
                'createproject' => Request::post('createproject', null, null),
                'createuser' => Request::post('createuser', null, null),
                'deleteuser' => Request::post('deleteuser', null, null)
            );

            Validation::addRule('name')->isRequired()->errorMessage(I18n::_('Name field is required.'));

            if (!Validation::validate($tData)) {
                Session::set(
                    'alert',
                    array(
                        'error',
                        implode('<br />', Validation::getErrorMessages(true))
                    )
                );
            } else {
                $this->load('App\\Models\\roleModel');

                $tId = $this->roleModel->insert($tData);

                Session::set(
                    'alert',
                    array(
                        'success',
                        'Record added.'
                    )
                );

                // redirect to newly created
                Http::redirect('manage/roles/edit/' . $tId, true);
                return;
            }
        }

        $this->view('manage/roles/add.cshtml');
    }

    /**
     * @ignore
     */
    private function roles_edit($uId)
    {
        $this->load('App\\Models\\roleModel');

        $tOriginalData = $this->roleModel->get($uId);
        if ($tOriginalData === false) {
            return false;
        }

        if (Request::$method === 'post') {
            $tData = array(
                'name' => Request::post('name', null, null),
                'createproject' => Request::post('createproject', null, null),
                'createuser' => Request::post('createuser', null, null),
                'deleteuser' => Request::post('deleteuser', null, null)
            );

            Validation::addRule('name')->isRequired()->errorMessage(I18n::_('Name field is required.'));

            if (!Validation::validate($tData)) {
                Session::set(
                    'alert',
                    array(
                        'error',
                        implode('<br />', Validation::getErrorMessages(true))
                    )
                );
            } else {
                $this->roleModel->update(
                    $uId,
                    $tData
                );

                Session::set(
                    'alert',
                    array(
                        'success',
                        'Record updated.'
                    )
                );
            }
        } else {
            $tData = $tOriginalData;
        }

        $this->set('id', $uId);
        $this->set('data', $tData);

        $this->view('manage/roles/edit.cshtml');
    }


    /**
     * @ignore
     */
    private function roles_remove($uId)
    {
        $this->load('App\\Models\\roleModel');

        $tOriginalData = $this->roleModel->get($uId);
        if ($tOriginalData === false) {
            return false;
        }

        $this->roleModel->delete(
            $uId
        );

        Session::set(
            'alert',
            array(
                'success',
                'Record removed.'
            )
        );

        // redirect to list
        Http::redirect('manage/roles', true);
        return;
    }
}
