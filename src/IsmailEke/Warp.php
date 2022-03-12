<?php

/*
*
* Warp Plugin
*
* Author: IsmailEke (İsmail Eke)
* GitHub: IsmailEke
* YouTube: İsmail Eke
* Discord: bulgasal#2359
*
*/

namespace IsmailEke;

use IsmailEke\command\WarpCommand;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Warp extends PluginBase {
    
    /** @var ?Config */
    public static ?Config $config;
    
    /**
     * @return void
     */
    public function onEnable () : void {
        $this->getLogger()->notice("Warp Plugin Online");
        $this->getServer()->getCommandMap()->register("warp", new WarpCommand());
        self::$config = new Config($this->getDataFolder() . "warp.yml", Config::YAML);
    }
    
    /**
     * @param string $searchWord
     * @param array $whereToSearch
     * 
     * @return string|null
     */
    public static function searchData (string $searchWord, array $whereToSearch) : ?string {
        $found = null;
        $word = strtolower($searchWord);
        $delta = PHP_INT_MAX;
        foreach ($whereToSearch as $data) {
            if(stripos($data, $word) === 0){
                $curDelta = strlen($data) - strlen($word);
                if($curDelta < $delta){
                    $found = $data;
                    $delta = $curDelta;
                }
                if($curDelta === 0){
                    break;
                }
            }
        }
        return $found;
    }
    
    /**
     * @return void
     */
    public function onDisable () : void {
        $this->getLogger()->alert("Warp Plugin Offline");
    }
}
