<?php

namespace IsmailEke\form;

use IsmailEke\Warp;
use pocketmine\form\Form;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\world\Position;

class WarpForm implements Form {
    
    /**
     * @return array
     */
    public function jsonSerialize () : array {
        $buttons = [];
        foreach (Warp::$config->getAll() as $warpName => $extraData) {
            $imageData = explode(":", $extraData)[6];
            if ($imageData === "noImage") {
                $buttons[] = ["text" => $warpName];
            } else {
                if (substr($imageData, 0, 4) === "http") {
                    $buttons[] = ["text" => $warpName, "image" => ["type" => "url", "data" => $imageData]];
                } else {
                    $buttons[] = ["text" => $warpName, "image" => ["type" => "path", "data" => $imageData]];
                }
            }
        }
        return [
            "type" => "form",
            "title" => "Warp",
            "content" => "",
            "buttons" => $buttons
        ];
    }
    
    /**
     * @param Player $player
     * @param mixed $data
     * 
     * @return void
     */
    public function handleResponse (Player $player, $data) : void {
        if (is_null($data)) return;
        $warpData = explode(":", Warp::$config->get(array_keys(Warp::$config->getAll())[$data]));
        if (Server::getInstance()->getWorldManager()->isWorldLoaded($warpData[5])) {
            $player->teleport(new Position(floatval($warpData[0]), floatval($warpData[1]), floatval($warpData[2]), Server::getInstance()->getWorldManager()->getWorldByName($warpData[5])), floatval($warpData[3]), floatval($warpData[4]));
        } else {
            Server::getInstance()->getWorldManager()->loadWorld($warpData[5]);
            $player->teleport(new Position(floatval($warpData[0]), floatval($warpData[1]), floatval($warpData[2]), Server::getInstance()->getWorldManager()->getWorldByName($warpData[5])), floatval($warpData[3]), floatval($warpData[4]));
        }
    }
}
