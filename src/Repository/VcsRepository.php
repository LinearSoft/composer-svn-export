<?php
/**
 * Created by PhpStorm.
 * User: meej
 * Date: 2/11/2016
 * Time: 3:29 PM
 */

namespace LinearSoft\Composer\SvnExport\Repository;


use Composer\Config;
use Composer\EventDispatcher\EventDispatcher;
use Composer\IO\IOInterface;
use Composer\Repository\Vcs\VcsDriverInterface;
use Composer\Repository\VcsRepository as CrVcsRepository;

class VcsRepository extends CrVcsRepository
{

    public function __construct(array $repoConfig, IOInterface $io, Config $config, EventDispatcher $dispatcher = null, array $drivers = null)
    {
        $drivers = $drivers ?: array(
            'svn-export'    => 'Composer\Repository\Vcs\SvnDriver',
        );
        parent::__construct($repoConfig, $io, $config, $dispatcher, $drivers);
    }

    protected function preProcess(VcsDriverInterface $driver, array $data, $identifier)
    {
        $data = parent::preProcess($driver,$data,$identifier);
        if(isset($data['source']['type']) && $data['source']['type'] === 'svn') $data['source']['type'] = 'svn-export';
        if(isset($data['dist']['type']) && $data['dist']['type'] === 'svn') $data['dist']['type'] = 'svn-export';
        return $data;
    }
}