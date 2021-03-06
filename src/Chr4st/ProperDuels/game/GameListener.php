<?php

declare(strict_types = 1);

namespace Chr4st\ProperDuels\game;

use Chr4st\ProperDuels\ProperDuels;

use pocketmine\event\Listener;
use pocketmine\event\player\{PlayerCommandPreprocessEvent, PlayerDeathEvent, PlayerQuitEvent};
use pocketmine\utils\TextFormat;

final class GameListener implements Listener{

	/**
	 * @priority HIGHEST
	 */
	public function onPlayerCommandPreprocess(PlayerCommandPreprocessEvent $event): void{
		$properDuels = ProperDuels::getInstance();
		if($event->getMessage()[0] === '/' and !$properDuels->getConfig()->getNested('match.allow-commands')){
			$player = $event->getPlayer();
			$session = $properDuels->getSessionManager()->get($player->getUniqueId()->getBytes());
			if($session !== null){
				$game = $session->getGame();
				if($game !== null){
					$event->cancel();

					$player->sendMessage(TextFormat::RED.'You can\'t use commands while in a match!');
				}
			}
		}
	}

	/**
	 * @priority HIGHEST
	 */
	public function onPlayerDeath(PlayerDeathEvent $event): void{
		$session = ProperDuels::getInstance()->getSessionManager()->get($event->getPlayer()->getUniqueId()->getBytes());
		if($session !== null){
			$game = $session->getGame();
			if($game !== null){
				$game->stop($session);

				$event->setKeepInventory(true);
				$event->setXpDropAmount(0);
			}
		}
	}

	/**
	 * @priority LOWEST
	 */
	public function onPlayerQuit(PlayerQuitEvent $event): void{
		$session = ProperDuels::getInstance()->getSessionManager()->get($event->getPlayer()->getUniqueId()->getBytes());
		if($session !== null){
			$game = $session->getGame();
			if($game !== null){
				$game->stop($session);
			}
		}
	}
}
