<?php

namespace DrupalProject\composer;

use Composer\Script\Event;
use DrupalFinder\DrupalFinder;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Drupal\Component\Utility\Crypt;

/**
 * Renamespace Project.
 */
class ProjectSetup {

  /**
   * Establish settings.local.php.
   *
   * @param \Composer\Script\Event $event
   *   Event object.
   */
  public static function localSettings(Event $event) {
    $fs = new Filesystem();
    $drupalFinder = new DrupalFinder();
    $drupalFinder->locateRoot(getcwd());
    $drupalRoot = $drupalFinder->getDrupalRoot();

    $exampleLocalSettingsPath = $drupalRoot . '/sites/example.settings.local.php';
    $localSettingsPath = $drupalRoot . '/sites/default/settings.local.php';

    if ($fs->exists($exampleLocalSettingsPath) && !$fs->exists($localSettingsPath)) {
      $fs->copy($exampleLocalSettingsPath, $localSettingsPath);
      $dbSettings = <<<'EOD'

      /**
       * Local development overrides.
       */
      if (!empty(getenv('LANDO_INFO'))) {
        $lando_info = json_decode(getenv('LANDO_INFO'), TRUE);
        $databases['default']['default'] = [
          'driver' => 'mysql',
          'database' => $lando_info['database']['creds']['database'],
          'username' => $lando_info['database']['creds']['user'],
          'password' => $lando_info['database']['creds']['password'],
          'host' => $lando_info['database']['internal_connection']['host'],
          'port' => $lando_info['database']['internal_connection']['port'],
        ];
      }
      EOD;

      file_put_contents($localSettingsPath, $dbSettings, FILE_APPEND);

      $hash_salt = Crypt::randomBytesBase64(55);

      file_put_contents($localSettingsPath, "\n\n\$settings['hash_salt'] = '" . $hash_salt . "';", FILE_APPEND);
      file_put_contents($localSettingsPath, "\n\n\$settings['config_sync_directory'] = '../config/sync';", FILE_APPEND);
    }
  }

  /**
   * Namespace project.
   */
  public static function namespace(Event $event) {
    // var_dump($event->getArguments());
    $arguments = $event->getArguments();
    $io = $event->getIO();

    if (empty($arguments)) {
      $io->writeError('You must pass namespace option. Ex: namespace="foo"');
      die;
    }

    if (count($arguments) > 1) {
      $io->writeError('This comman only accepts one argument, the namespace for the project.');
      die;
    }

    $projectNamespace = $arguments['0'];
    $finder = new Finder();
    $fs = new Filesystem();
    $drupalFinder = new DrupalFinder();
    $drupalFinder->locateRoot(getcwd());
    $composerRoot = $drupalFinder->getComposerRoot();
    $scriptPath = $composerRoot . '/scripts/composer/ProjectSetup.php';
    $searchReplaceKey = 'adapt-drupal-starter';
    $themeBase = $composerRoot . '/web/themes/custom/';
    $themeDir = $themeBase . $searchReplaceKey;

    // Create custom theme directory.
    if ($fs->exists($themeDir)) {
      $fs->rename($themeDir, $themeBase . $projectNamespace);
    }

    // Simple search + replace for pre-defined palceholder.
    foreach ($finder->files()->in($composerRoot)->ignoreDotFiles(FALSE)->contains($searchReplaceKey) as $file) {
      // @TODO: Hardcoding this is hacky.
      if ($file->getRealPath() != $scriptPath) {
        $fileContents = file_get_contents($file->getRealPath());
        $fileContents = str_replace($searchReplaceKey, $projectNamespace, $fileContents);
        file_put_contents($file->getRealPath(), $fileContents);
        unset($fileContents);
      }
    }

    // Reset finder as it doesn't reset it's own state.
    $finder = new Finder();

    // Rename any placeholdered files.
    foreach ($finder->files()->in($composerRoot)->name("*{$searchReplaceKey}*") as $file) {
      $oldFileName = $file->getFileName();
      $newFileName = str_replace($searchReplaceKey, $projectNamespace, $oldFileName);
      $fullPath = $themeBase . $projectNamespace . '/' . $newFileName;
      $fs->rename($file->getRealPath(), $fullPath);
    }
  }

}
