<?php

namespace ItsRealNise\Spyglass\item;

use pocketmine\item\Item;
use pocketmine\item\ItemIdentifier;

use ItsRealNise\Spyglass\CustomIds;

class Spyglass extends Item
{

    public function __construct(int $meta = 0)
    {
        parent::__construct(new ItemIdentifier(CustomIds::SPYGLASS, 0), "Spyglass");
    }

    public function getMaxStackSize(): int
    {
        return 1;
    }
}
