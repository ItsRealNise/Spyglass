<?php

namespace ItsRealNise\Spyglass\item;

use pocketmine\item\Item;
use pocketmine\item\Releasable;
use pocketmine\player\Player;
use pocketmine\item\ItemIdentifier;

use ItsRealNise\Spyglass\CustomIds;

class Spyglass extends Item implements Releasable
{

    public function __construct()
    {
        parent::__construct(new ItemIdentifier(CustomIds::SPYGLASS, 0), "Spyglass");
    }

    public function getMaxStackSize(): int
    {
        return 1;
    }
    
    public function canStartUsingItem(Player $player) : bool{
		return true;
	}
}
