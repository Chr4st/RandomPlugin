<?php

declare(strict_types = 1);

namespace Chr4st\ProperDuels\command\arena\subcommand;

use CortexPE\Commando\BaseSubCommand;

use Chr4st\ProperDuels\ProperDuels;

use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class ListSubCommand extends BaseSubCommand{

	public function onRun(CommandSender $sender, string $aliasUsed, array $args): void{
		if(!$this->plugin instanceof ProperDuels){
			throw new \UnexpectedValueException('This command wasn\'t created by ' . ProperDuels::class);
		}
		$arenas = array_keys($this->plugin->getArenaManager()->all());
		$count = count($arenas);
		if($count === 0){
			$sender->sendMessage(TextFormat::RED.'There are no arenas');
			return;
		}

		$sender->sendMessage("There are $count arena(s):");
		$sender->sendMessage(implode(", ", $arenas));
	}

	public function prepare(): void{
		$this->setPermission('properduels.command.arena.list');
	}
}
