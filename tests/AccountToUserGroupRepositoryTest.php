<?php
/**
 * sysPass
 *
 * @author    nuxsmin
 * @link      https://syspass.org
 * @copyright 2012-2018, Rubén Domínguez nuxsmin@$syspass.org
 *
 * This file is part of sysPass.
 *
 * sysPass is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * sysPass is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 *  along with sysPass.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace SP\Tests;

use DI\DependencyException;
use SP\Account\AccountRequest;
use SP\Core\Exceptions\ConstraintException;
use SP\DataModel\ItemData;
use SP\Repositories\Account\AccountToUserGroupRepository;
use SP\Storage\DatabaseConnectionData;

/**
 * Class AccountToUserGroupRepositoryTest
 *
 * Tests de integración para la comprobación de operaciones de grupos de usuarios asociados a cuentas
 *
 * @package SP\Tests
 */
class AccountToUserGroupRepositoryTest extends DatabaseTestCase
{
    /**
     * @var AccountToUserGroupRepository
     */
    private static $accountToUserGroupRepository;

    /**
     * @throws DependencyException
     * @throws \DI\NotFoundException
     * @throws \SP\Core\Context\ContextException
     */
    public static function setUpBeforeClass()
    {
        $dic = setupContext();

        // Datos de conexión a la BBDD
        self::$databaseConnectionData = $dic->get(DatabaseConnectionData::class);

        // Inicializar el repositorio
        self::$accountToUserGroupRepository = $dic->get(AccountToUserGroupRepository::class);
    }

    /**
     * Comprobar la actualización de grupos de usuarios por Id de cuenta
     *
     * @throws \SP\Core\Exceptions\ConstraintException
     * @throws \SP\Core\Exceptions\QueryException
     */
    public function testUpdate()
    {
        $accountRequest = new AccountRequest();
        $accountRequest->id = 1;
        $accountRequest->userGroupsView = [1, 2, 3];

        $this->assertEquals(3, self::$accountToUserGroupRepository->update($accountRequest));

        $userGroups = self::$accountToUserGroupRepository->getUserGroupsByAccountId($accountRequest->id);

        $this->assertCount(3, $userGroups);
        $this->assertInstanceOf(ItemData::class, $userGroups[0]);
        $this->assertEquals(0, (int)$userGroups[0]->isEdit);
        $this->assertInstanceOf(ItemData::class, $userGroups[1]);
        $this->assertEquals(0, (int)$userGroups[1]->isEdit);
        $this->assertInstanceOf(ItemData::class, $userGroups[2]);
        $this->assertEquals(0, (int)$userGroups[2]->isEdit);

        $this->expectException(ConstraintException::class);

        $accountRequest->userGroupsView = [10];

        self::$accountToUserGroupRepository->update($accountRequest);

        $accountRequest->id = 3;
        $accountRequest->userGroupsView = [1, 2, 3];

        self::$accountToUserGroupRepository->update($accountRequest);
    }

    /**
     * Comprobar la obtención de grupos de usuarios por Id de cuenta
     */
    public function testGetUsersByAccountId()
    {
        $userGroups = self::$accountToUserGroupRepository->getUserGroupsByAccountId(1);

        $this->assertCount(1, $userGroups);
        $this->assertInstanceOf(ItemData::class, $userGroups[0]);

        $userGroupsView = array_filter($userGroups, function ($user) {
            return (int)$user->isEdit === 0;
        });

        $this->assertCount(0, $userGroupsView);

        $userGroupsEdit = array_filter($userGroups, function ($user) {
            return (int)$user->isEdit === 1;
        });

        $this->assertCount(1, $userGroupsEdit);

        $userGroups = self::$accountToUserGroupRepository->getUserGroupsByAccountId(2);

        $this->assertCount(1, $userGroups);
        $this->assertInstanceOf(ItemData::class, $userGroups[0]);

        $userGroupsView = array_filter($userGroups, function ($user) {
            return (int)$user->isEdit === 0;
        });

        $this->assertCount(1, $userGroupsView);

        $userGroupsEdit = array_filter($userGroups, function ($user) {
            return (int)$user->isEdit === 1;
        });

        $this->assertCount(0, $userGroupsEdit);

        $userGroups = self::$accountToUserGroupRepository->getUserGroupsByAccountId(3);

        $this->assertCount(0, $userGroups);
    }

    /**
     * Comprobar la actualización de grupos de usuarios con permisos de modificación por Id de cuenta
     *
     * @throws \SP\Core\Exceptions\ConstraintException
     * @throws \SP\Core\Exceptions\QueryException
     */
    public function testUpdateEdit()
    {
        $accountRequest = new AccountRequest();
        $accountRequest->id = 2;
        $accountRequest->userGroupsEdit = [2, 3];

        $this->assertEquals(3, self::$accountToUserGroupRepository->updateEdit($accountRequest));

        $userGroups = self::$accountToUserGroupRepository->getUserGroupsByAccountId($accountRequest->id);

        $this->assertCount(2, $userGroups);
        $this->assertInstanceOf(ItemData::class, $userGroups[0]);
        $this->assertEquals(1, (int)$userGroups[0]->isEdit);
        $this->assertInstanceOf(ItemData::class, $userGroups[1]);
        $this->assertEquals(1, (int)$userGroups[1]->isEdit);

        $this->expectException(ConstraintException::class);

        // Comprobar que se lanza excepción al añadir usuarios no existentes
        $accountRequest->userGroupsEdit = [10];

        self::$accountToUserGroupRepository->updateEdit($accountRequest);

        // Comprobar que se lanza excepción al añadir usuarios a cuenta no existente
        $accountRequest->id = 3;
        $accountRequest->userGroupsEdit = [2, 3];

        self::$accountToUserGroupRepository->updateEdit($accountRequest);
    }

    /**
     * Comprobar la eliminación de grupos de usuarios por Id de cuenta
     *
     * @throws \SP\Core\Exceptions\ConstraintException
     * @throws \SP\Core\Exceptions\QueryException
     */
    public function testDeleteByAccountId()
    {
        $this->assertEquals(1, self::$accountToUserGroupRepository->deleteByAccountId(1));
        $this->assertCount(0, self::$accountToUserGroupRepository->getUserGroupsByAccountId(1));

        $this->assertEquals(0, self::$accountToUserGroupRepository->deleteByAccountId(10));

        $this->assertEquals(1, $this->conn->getRowCount('AccountToUserGroup'));
    }

    /**
     * Comprobar la insercción de grupos de usuarios con permisos de modificación por Id de cuenta
     *
     * @throws \SP\Core\Exceptions\ConstraintException
     * @throws \SP\Core\Exceptions\QueryException
     */
    public function testAddEdit()
    {
        $accountRequest = new AccountRequest();
        $accountRequest->id = 2;
        $accountRequest->userGroupsEdit = [1, 2, 3];

        self::$accountToUserGroupRepository->addEdit($accountRequest);

        $userGroups = self::$accountToUserGroupRepository->getUserGroupsByAccountId($accountRequest->id);

        $this->assertCount(3, $userGroups);
        $this->assertInstanceOf(ItemData::class, $userGroups[0]);
        $this->assertInstanceOf(ItemData::class, $userGroups[1]);
        $this->assertInstanceOf(ItemData::class, $userGroups[2]);

        $this->expectException(ConstraintException::class);

        // Comprobar que se lanza excepción al añadir usuarios no existentes
        $accountRequest->userGroupsEdit = [10];

        self::$accountToUserGroupRepository->addEdit($accountRequest);

        // Comprobar que se lanza excepción al añadir grupos de usuarios a cuenta no existente
        $accountRequest->id = 3;
        $accountRequest->userGroupsEdit = [1, 2, 3];

        self::$accountToUserGroupRepository->addEdit($accountRequest);
    }

    /**
     * Comprobar la insercción de grupos de usuarios por Id de cuenta
     *
     * @throws \SP\Core\Exceptions\ConstraintException
     * @throws \SP\Core\Exceptions\QueryException
     */
    public function testAdd()
    {
        $accountRequest = new AccountRequest();
        $accountRequest->id = 2;
        $accountRequest->userGroupsView = [1, 2, 3];

        $this->assertEquals(3, self::$accountToUserGroupRepository->add($accountRequest));

        $userGroups = self::$accountToUserGroupRepository->getUserGroupsByAccountId($accountRequest->id);

        $this->assertCount(3, $userGroups);
        $this->assertInstanceOf(ItemData::class, $userGroups[0]);
        $this->assertInstanceOf(ItemData::class, $userGroups[1]);
        $this->assertInstanceOf(ItemData::class, $userGroups[2]);

        $this->expectException(ConstraintException::class);

        // Comprobar que se lanza excepción al añadir usuarios no existentes
        $accountRequest->userGroupsView = [10];

        self::$accountToUserGroupRepository->add($accountRequest);

        // Comprobar que se lanza excepción al añadir grupos de usuarios a cuenta no existente
        $accountRequest->id = 3;
        $accountRequest->userGroupsView = [1, 2, 3];

        self::$accountToUserGroupRepository->add($accountRequest);
    }

    /**
     * Comprobar la eliminación de grupos de usuarios con permisos de modificación por Id de cuenta
     *
     * @throws \SP\Core\Exceptions\ConstraintException
     * @throws \SP\Core\Exceptions\QueryException
     */
    public function testDeleteEditByAccountId()
    {
        $this->assertEquals(1, self::$accountToUserGroupRepository->deleteEditByAccountId(1));
        $this->assertCount(0, self::$accountToUserGroupRepository->getUserGroupsByAccountId(1));

        $this->assertEquals(0, self::$accountToUserGroupRepository->deleteEditByAccountId(10));

        $this->assertEquals(1, $this->conn->getRowCount('AccountToUserGroup'));
    }

    /**
     * Comprobar la obtención de grupos de usuarios por Id de grupo
     */
    public function testGetUserGroupsByUserGroupId()
    {
        $userGroups = self::$accountToUserGroupRepository->getUserGroupsByUserGroupId(2);

        $this->assertCount(2, $userGroups);

        $userGroups = self::$accountToUserGroupRepository->getUserGroupsByUserGroupId(3);

        $this->assertCount(0, $userGroups);

        $userGroups = self::$accountToUserGroupRepository->getUserGroupsByUserGroupId(10);

        $this->assertCount(0, $userGroups);
    }

    /**
     * Comprobar la eliminación de grupos de usuarios por Id de grupo
     *
     * @throws ConstraintException
     * @throws \SP\Core\Exceptions\QueryException
     */
    public function testDeleteByUserGroupId()
    {
        $this->assertEquals(2, self::$accountToUserGroupRepository->deleteByUserGroupId(2));

        $this->assertEquals(0, self::$accountToUserGroupRepository->deleteByUserGroupId(1));

        $this->assertEquals(0, self::$accountToUserGroupRepository->deleteByUserGroupId(10));
    }
}