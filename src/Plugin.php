<?php
/**
 * Created by PhpStorm.
 * User: meej
 * Date: 2/10/2016
 * Time: 3:18 PM
 */

namespace LinearSoft\Composer\SvnExport;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\Util\Filesystem;
use Composer\Util\ProcessExecutor;

class Plugin implements PluginInterface
{
    /** @var  IOInterface */
    protected $io;

    /**
     * @inheritdoc
     */
    public function activate(Composer $composer, IOInterface $io)
    {
        $this->io = $io;

        //Extend download manager
        $dm = $composer->getDownloadManager();
        $executor = new ProcessExecutor($io);
        $fs = new Filesystem($executor);
        $config = $composer->getConfig();
        $dm->setDownloader('svn-export', new Downloader($io, $config, $executor, $fs));

        //Extend RepositoryManager Classes
        $rm = $composer->getRepositoryManager();
        $rm->setRepositoryClass('svn-export', 'LinearSoft\Composer\SvnExport\Repository\VcsRepository');
        $rm->setRepositoryClass('svn-export-composer', 'LinearSoft\Composer\SvnExport\Repository\ComposerRepository');

        //Load Extra Data
        $extra = $composer->getPackage()->getExtra();
        if (isset($extra['svn-export-repositories']) && is_array($extra['svn-export-repositories'])) {
            foreach ($extra['svn-export-repositories'] as $index => $repoConfig) {
                $this->validateRepositories($index, $repoConfig);

                if(isset($repoConfig['name'])) $name = $repoConfig['name'];
                else $name = is_int($index) ? preg_replace('{^https?://}i', '', $repoConfig['url']) : $index;

                if($repoConfig['type'] === 'svn') $repoConfig['type'] = 'svn-export';
                else $repoConfig['type'] = 'svn-export-composer';

                $repo = $rm->createRepository($repoConfig['type'],$repoConfig);
                $rm->addRepository($repo);
                $this->io->write("Added SvnExport repo: $name");
            }
        }
    }

    /**
     * @param int|string  $index
     * @param mixed|array $repoConfig
     *
     * @throws \UnexpectedValueException
     */
    protected function validateRepositories($index, $repoConfig)
    {
        if (!is_array($repoConfig)) {
            throw new \UnexpectedValueException('SvnExport Repository '.$index.' ('.json_encode($repoConfig).') should be an array, '.gettype($repoConfig).' given');
        }
        if (!isset($repoConfig['type'])) {
            throw new \UnexpectedValueException('SvnExport Repository '.$index.' ('.json_encode($repoConfig).') must have a type defined');
        }
        if ($repoConfig['type'] !== 'svn' && $repoConfig['type'] !== 'composer') {
            throw new \UnexpectedValueException('SvnExport Repository '.$index.' ('.json_encode($repoConfig).') must have a type of "svn" or "composer"');
        }
        if (!isset($repoConfig['url'])) {
            throw new \UnexpectedValueException('SvnExport Repository '.$index.' ('.json_encode($repoConfig).') must have a url defined');
        }
    }


}