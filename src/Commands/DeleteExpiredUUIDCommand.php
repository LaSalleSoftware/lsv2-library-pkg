<?php

/**
 * This file is part of the Lasalle Software library package. 
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright  (c) 2019-2020 The South LaSalle Trading Corporation
 * @license    http://opensource.org/licenses/MIT
 * @author     Bob Bloom
 * @email      bob.bloom@lasallesoftware.ca
 * @link       https://lasallesoftware.ca
 * @link       https://packagist.org/packages/lasallesoftware/ls-library-pkg
 * @link       https://github.com/LaSalleSoftware/ls-library-pkg
 *
 */

namespace Lasallesoftware\Library\Commands;

// LaSalle Software
use Lasallesoftware\Library\UniversallyUniqueIDentifiers\Models\Uuid;
use Lasallesoftware\Library\Common\Commands\CommonCommand;

/**
 * Class DeleteExpiredUUIDCommand
 *
 * Deletes uuids database table records that have become inactive. 
 *
 * This command is supposed to be run automatically via scheduler.
 *
 * @package Lasallesoftware\Library\Commands\DeleteExpiredUUIDCommand
 */
class DeleteExpiredUUIDCommand extends CommonCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'lslibrary:deleteexpireduuid';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete expired uuid records.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $uuid = new Uuid();
        $uuid->deleteExpired();


        $this->info('Expired uuid records cleared!');
    }
}
