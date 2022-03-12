<?php

namespace IsmailEke\command;

use IsmailEke\Warp;
use IsmailEke\form\WarpForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\CommandException;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use pocketmine\Server;

class WarpCommand extends Command {
	
	public function __construct () {
		parent::__construct("warp", "Warp Command.", "/warp");
	}
	
	/**
	 * @param string[] $args
	 *
	 * @return mixed
	 * @throws CommandException
	 */
	
	public function execute (CommandSender $sender, string $commandLabel, array $args) {
		if (count($args) < 1) {
            $sender->sendForm(new WarpForm());
        } elseif (count($args) >= 2) {
            if (Server::getInstance()->isOp($sender->getName())) {
                switch (strtolower($args[0])) {
                    case "create":
                        if (count($args) < 3) return;
                        $text = implode(" ", array_slice($args, 2));
                        Warp::$config->set(ltrim($text), $sender->getPosition()->getX() . ":" . $sender->getPosition()->getY() . ":" . $sender->getPosition()->getZ() . ":" . $sender->getLocation()->getYaw() . ":" . $sender->getLocation()->getPitch() . ":" . $sender->getWorld()->getFolderName() . ":" . (strtolower($args[1]) === "no" ? "noImage" : $args[1]));
                        Warp::$config->save();
                        $sender->sendMessage("§aWarp successfully created.");
                    break;
                    case "delete":
                        $text = implode(" ", array_slice($args, 1));
                        $found = Warp::searchData($text, array_keys(Warp::$config->getAll()));
                        if (!is_null($found)) {
                            Warp::$config->remove($found);
                            Warp::$config->save();
                            $sender->sendMessage("§eWarp successfully deleted.");
                        } else {
                            $sender->sendMessage("§7Warp name §c" . $text . " §7not found!");
                        }
                    break;
                    default:
                        $sender->sendMessage("§7Usage:\n\n* Warp Create:\n\n# If Has Image:\n/warp create <string:imageLink> <warpName>\n\n# If No Image:\n/warp create <string:no> <warpName>\n\n* Warp Delete:\n\n/warp delete <warpName>");
                    break;
                }
            }
        }
	}
}
