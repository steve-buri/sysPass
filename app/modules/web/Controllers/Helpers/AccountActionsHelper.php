<?php

namespace SP\Modules\Web\Controllers\Helpers;

use SP\Account\AccountsSearchItem;
use SP\Core\Acl\Acl;
use SP\Core\Acl\ActionsInterface;
use SP\Core\UI\ThemeIconsBase;
use SP\Html\DataGrid\DataGridAction;
use SP\Html\DataGrid\DataGridActionType;

/**
 * Class AccountIconsHelper
 *
 * @package SP\Modules\Web\Controllers\Helpers
 */
class AccountActionsHelper extends HelperBase
{
    /**
     * @var ThemeIconsBase
     */
    protected $icons;

    /**
     * @return DataGridAction
     */
    public function getViewAction()
    {
        $action = new DataGridAction();
        $action->setId(ActionsInterface::ACCOUNT_VIEW);
        $action->setType(DataGridActionType::VIEW_ITEM);
        $action->setName(__('Detalles de Cuenta'));
        $action->setTitle(__('Detalles de Cuenta'));
        $action->addClass('btn-action');
        $action->setIcon($this->icons->getIconView());
        $action->setReflectionFilter(AccountsSearchItem::class, 'isShowView');
        $action->addData('action-route', Acl::getActionRoute(ActionsInterface::ACCOUNT_VIEW));
        $action->addData('action-sk', $this->view->sk);
        $action->addData('onclick', Acl::getActionRoute(ActionsInterface::ACCOUNT_VIEW));
        $action->addAttribute('type', 'button');

        return $action;
    }

    /**
     * @return DataGridAction
     */
    public function getViewPassAction()
    {
        $action = new DataGridAction();
        $action->setId(ActionsInterface::ACCOUNT_VIEW_PASS);
        $action->setType(DataGridActionType::VIEW_ITEM);
        $action->setName(__('Ver Clave'));
        $action->setTitle(__('Ver Clave'));
        $action->addClass('btn-action');
        $action->setIcon($this->icons->getIconViewPass());
        $action->setReflectionFilter(AccountsSearchItem::class, 'isShowViewPass');
        $action->addData('action-route', Acl::getActionRoute(ActionsInterface::ACCOUNT_VIEW_PASS));
        $action->addData('action-full', 1);
        $action->addData('action-sk', $this->view->sk);
        $action->addData('onclick', Acl::getActionRoute(ActionsInterface::ACCOUNT_VIEW_PASS));
        $action->addAttribute('type', 'button');

        return $action;
    }

    /**
     * @return DataGridAction
     */
    public function getEditPassAction()
    {
        $action = new DataGridAction();
        $action->setId(ActionsInterface::ACCOUNT_EDIT_PASS);
        $action->setType(DataGridActionType::VIEW_ITEM);
        $action->setName(__('Modificar Clave de Cuenta'));
        $action->setTitle(__('Modificar Clave de Cuenta'));
        $action->addClass('btn-action');
        $action->setIcon($this->icons->getIconEditPass());
        $action->setReflectionFilter(AccountsSearchItem::class, 'isShowViewPass');
        $action->addData('action-route', Acl::getActionRoute(ActionsInterface::ACCOUNT_EDIT_PASS));
        $action->addData('action-sk', $this->view->sk);
        $action->addData('onclick', Acl::getActionRoute(ActionsInterface::ACCOUNT_EDIT_PASS));
        $action->addAttribute('type', 'button');

        return $action;
    }

    /**
     * @return DataGridAction
     */
    public function getRestoreAction()
    {
        $action = new DataGridAction();
        $action->setId(ActionsInterface::ACCOUNT_EDIT_RESTORE);
        $action->setType(DataGridActionType::VIEW_ITEM);
        $action->setName(__('Restaurar cuenta desde este punto'));
        $action->setTitle(__('Restaurar cuenta desde este punto'));
        $action->addClass('btn-action');
        $action->setIcon($this->icons->getIconRestore());
        $action->addData('action-route', 'account/saveEditRestore');
        $action->addData('action-sk', $this->view->sk);
        $action->addData('onclick', 'account/saveEditRestore');
        $action->addAttribute('type', 'button');

        return $action;
    }

    /**
     * @return DataGridAction
     */
    public function getSaveAction()
    {
        $action = new DataGridAction();
        $action->setId(ActionsInterface::ACCOUNT);
        $action->setType(DataGridActionType::VIEW_ITEM);
        $action->setName(__('Guardar'));
        $action->setTitle(__('Guardar'));
        $action->addClass('btn-action');
        $action->setIcon($this->icons->getIconSave());
        $action->addData('action-route', 'account/save');
        $action->addData('action-sk', $this->view->sk);
        $action->addData('onclick', 'account/save');
        $action->addAttribute('type', 'submit');

        return $action;
    }

    /**
     * @return DataGridAction
     */
    public function getCopyPassAction()
    {
        // Añadir la clase para usar el portapapeles
        $ClipboardIcon = $this->icons->getIconClipboard()->setClass('clip-pass-button');

        $action = new DataGridAction();
        $action->setId(ActionsInterface::ACCOUNT_VIEW_PASS);
        $action->setType(DataGridActionType::VIEW_ITEM);
        $action->setName(__('Copiar Clave en Portapapeles'));
        $action->setTitle(__('Copiar Clave en Portapapeles'));
        $action->addClass('btn-action');
        $action->setIcon($ClipboardIcon);
        $action->setReflectionFilter(AccountsSearchItem::class, 'isShowCopyPass');
        $action->addData('action-route', Acl::getActionRoute(ActionsInterface::ACCOUNT_COPY_PASS));
        $action->addData('action-full', 0);
        $action->addData('action-sk', $this->view->sk);
        $action->addData('useclipboard', '1');
        $action->addAttribute('type', 'button');

        return $action;
    }

    /**
     * @return DataGridAction
     */
    public function getEditAction()
    {
        $action = new DataGridAction();
        $action->setId(ActionsInterface::ACCOUNT_EDIT);
        $action->setType(DataGridActionType::EDIT_ITEM);
        $action->setName(__('Editar Cuenta'));
        $action->setTitle(__('Editar Cuenta'));
        $action->addClass('btn-action');
        $action->setIcon($this->icons->getIconEdit());
        $action->setReflectionFilter(AccountsSearchItem::class, 'isShowEdit');
        $action->addData('action-route', Acl::getActionRoute(ActionsInterface::ACCOUNT_EDIT));
        $action->addData('action-sk', $this->view->sk);
        $action->addData('onclick', Acl::getActionRoute(ActionsInterface::ACCOUNT_EDIT));
        $action->addAttribute('type', 'button');

        return $action;
    }

    /**
     * @return DataGridAction
     */
    public function getPublicLinkAction()
    {
        $action = new DataGridAction();
        $action->setId(ActionsInterface::PUBLICLINK_CREATE);
        $action->setName(__('Crear Enlace Público'));
        $action->setTitle(__('Crear Enlace Público'));
        $action->addClass('btn-action');
        $action->setIcon($this->icons->getIconPublicLink());
        $action->addData('action-route', Acl::getActionRoute(ActionsInterface::PUBLICLINK_CREATE));
        $action->addData('action-sk', $this->view->sk);
        $action->addData('onclick', 'link/save');
        $action->addData('action-next', Acl::getActionRoute(ActionsInterface::ACCOUNT_VIEW));
        $action->addAttribute('type', 'button');

        return $action;
    }

    /**
     * @return DataGridAction
     */
    public function getPublicLinkRefreshAction()
    {
        $action = new DataGridAction();
        $action->setId(ActionsInterface::PUBLICLINK_REFRESH);
        $action->setName(__('Actualizar Enlace Público'));
        $action->setTitle(__('Actualizar Enlace Público'));
        $action->setIcon($this->icons->getIconPublicLink());
        $action->addData('action-route', Acl::getActionRoute(ActionsInterface::PUBLICLINK_REFRESH));
        $action->addData('action-sk', $this->view->sk);
        $action->addData('onclick', 'link/refresh');
        $action->addData('action-next', Acl::getActionRoute(ActionsInterface::ACCOUNT_VIEW));
        $action->addAttribute('type', 'button');

        return $action;
    }

    /**
     * @return DataGridAction
     */
    public function getCopyAction()
    {
        $action = new DataGridAction();
        $action->setId(ActionsInterface::ACCOUNT_COPY);
        $action->setType(DataGridActionType::NEW_ITEM);
        $action->setName(__('Copiar Cuenta'));
        $action->setTitle(__('Copiar Cuenta'));
        $action->addClass('btn-action');
        $action->setIcon($this->icons->getIconCopy());
        $action->setReflectionFilter(AccountsSearchItem::class, 'isShowCopy');
        $action->addData('action-route', Acl::getActionRoute(ActionsInterface::ACCOUNT_COPY));
        $action->addData('action-sk', $this->view->sk);
        $action->addData('onclick', Acl::getActionRoute(ActionsInterface::ACCOUNT_COPY));
        $action->addAttribute('type', 'button');

        return $action;
    }

    /**
     * @return DataGridAction
     */
    public function getDeleteAction()
    {
        $action = new DataGridAction();
        $action->setId(ActionsInterface::ACCOUNT_DELETE);
        $action->setType(DataGridActionType::DELETE_ITEM);
        $action->setName(__('Eliminar Cuenta'));
        $action->setTitle(__('Eliminar Cuenta'));
        $action->addClass('btn-action');
        $action->setIcon($this->icons->getIconDelete());
        $action->setReflectionFilter(AccountsSearchItem::class, 'isShowDelete');
        $action->addData('action-route', Acl::getActionRoute(ActionsInterface::ACCOUNT_DELETE));
        $action->addData('action-sk', $this->view->sk);
        $action->addData('onclick', Acl::getActionRoute(ActionsInterface::ACCOUNT_DELETE));
        $action->addAttribute('type', 'button');

        return $action;
    }

    /**
     * @return DataGridAction
     */
    public function getRequestAction()
    {
        $action = new DataGridAction();
        $action->setId(ActionsInterface::ACCOUNT_REQUEST);
        $action->setName(__('Solicitar Modificación'));
        $action->setTitle(__('Solicitar Modificación'));
        $action->addClass('btn-action');
        $action->setIcon($this->icons->getIconEmail());
        $action->setReflectionFilter(AccountsSearchItem::class, 'isShowRequest');
        $action->addData('action-route', Acl::getActionRoute(ActionsInterface::ACCOUNT_REQUEST));
        $action->addData('action-sk', $this->view->sk);
        $action->addData('onclick', Acl::getActionRoute(ActionsInterface::ACCOUNT_VIEW));
        $action->addAttribute('type', 'submit');

        return $action;
    }

    /**
     * @return DataGridAction
     */
    public function getBackAction()
    {
        $action = new DataGridAction();
        $action->setId('btnBack');
        $action->setName(__('Atrás'));
        $action->setTitle(__('Atrás'));
        $action->addClass('btn-action');
        $action->setIcon($this->icons->getIconBack());
        $action->addData('action-route', Acl::getActionRoute(ActionsInterface::ACCOUNT_VIEW));
        $action->addData('action-sk', $this->view->sk);
        $action->addData('onclick', Acl::getActionRoute(ActionsInterface::ACCOUNT_VIEW));
        $action->addAttribute('type', 'button');

        return $action;
    }

    /**
     * Initialize class
     */
    protected function initialize()
    {
        $this->icons = $this->view->getTheme()->getIcons();
    }
}