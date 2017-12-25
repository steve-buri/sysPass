<?php
/**
 * sysPass
 *
 * @author    nuxsmin
 * @link      http://syspass.org
 * @copyright 2012-2017, Rubén Domínguez nuxsmin@$syspass.org
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

namespace SP\Storage;

use PDOStatement;
use SP\Core\Exceptions\SPException;

/**
 * Interface DatabaseInterface
 *
 * @package SP\Storage
 */
interface DatabaseInterface
{
    /**
     * Performs a DB query
     *
     * @param QueryData $queryData  Query data
     * @param bool      $getRawData Don't fetch records and return prepared statement
     * @return PDOStatement|array
     * @throws SPException
     */
    public function doQuery(QueryData $queryData, $getRawData = false);

    /**
     * Returns the total number of records
     *
     * @param QueryData $queryData Query data
     * @return int Records count
     */
    public function getFullRowCount(QueryData $queryData);

    /**
     * @return DBStorageInterface
     */
    public function getDbHandler();

    /**
     * @return int
     */
    public function getNumRows();

    /**
     * @return int
     */
    public function getNumFields();

    /**
     * @return array
     */
    public function getLastResult();

    /**
     * @return int
     */
    public function getLastId();
}