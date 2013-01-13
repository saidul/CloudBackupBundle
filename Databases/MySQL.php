<?php
namespace Dizda\CloudBackupBundle\Databases;

use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("dizda.cloudbackup.database.mysql");
 */
class MySQL extends BaseDatabase
{
    const DB_PATH = 'mysql';

    private $allDatabases;
    private $database;
    private $auth = '';


    /**
     * @DI\InjectParams({
     *     "allDatabases" = @DI\Inject("%dizda_cloud_backup.databases.mongodb.all_databases%"),
     *     "database"     = @DI\Inject("%dizda_cloud_backup.databases.mongodb.database%"),
     *     "user"         = @DI\Inject("%dizda_cloud_backup.databases.mongodb.db_user%"),
     *     "password"     = @DI\Inject("%dizda_cloud_backup.databases.mongodb.db_password%")
     * })
     */
    public function __construct($allDatabases, $database, $user, $password)
    {
        parent::__construct();

        $this->allDatabases = $allDatabases;
        $this->database     = $database;
        $this->auth         = '';

        if($this->allDatabases)
        {
            $this->database = '--all-databases';
        }else{
            $this->database = $this->database;
        }

        /* if user is set, we add authentification */
        if($user)
        {
            $this->auth = sprintf('-u%s', $user);

            if($password) $this->auth = sprintf('-u%s -p%s', $user, $password);
        }

    }


    public function dump()
    {
        parent::prepare();

        $cmd    = sprintf('mysqldump %s %s > %s',
            $this->auth,
            $this->database,
            $this->dataPath);

        exec($cmd);
    }

}