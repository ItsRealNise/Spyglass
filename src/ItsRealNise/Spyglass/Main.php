<?php

namespace ItsRealNise\Spyglass;

use pocketmine\block\BlockBreakInfo;
use pocketmine\block\BlockFactory;
use pocketmine\block\BlockLegacyIds as Ids;
use pocketmine\block\BlockIdentifier as BID;
use pocketmine\inventory\CreativeInventory;
use pocketmine\block\BlockToolType;
use pocketmine\item\ToolTier;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIdentifier;
use pocketmine\network\mcpe\convert\RuntimeBlockMapping;
use pocketmine\network\mcpe\convert\ItemTranslator;
use pocketmine\data\bedrock\LegacyBlockIdToStringIdMap;
use pocketmine\data\bedrock\LegacyToStringBidirectionalIdMap;
use pocketmine\block\utils\TreeType;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;
use pocketmine\utils\Config;
use ReflectionClass;
use ReflectionMethod;
use const pocketmine\BEDROCK_DATA_PATH;

use ItsRealNise\Spyglass\item\Spyglass;

class Main extends PluginBase {
    
    public function onLoad() : void{
        $this->initItems();
        //$this->initBlocks();
    }
    
    public function initItems(): void
    {
        $reflectionClass = new ReflectionClass(ItemTranslator::getInstance());
        $property = $reflectionClass->getProperty("simpleCoreToNetMapping");
        $property->setAccessible(true);
        $value = $property->getValue(ItemTranslator::getInstance());

        $property2 = $reflectionClass->getProperty("simpleNetToCoreMapping");
        $property2->setAccessible(true);
        $value2 = $property2->getValue(ItemTranslator::getInstance());

        $runtimeIds = json_decode(file_get_contents(BEDROCK_DATA_PATH . 'required_item_list.json'), true);
        $itemIds = json_decode(file_get_contents(BEDROCK_DATA_PATH . 'item_id_map.json'), true);

        foreach ([
                     "minecraft:spyglass" => CustomIds::SPYGLASS
                 ] as $name => $id) {
            $itemIds[$name] = $id;
            $itemId = $itemIds[$name];
            $runtimeId = $runtimeIds[$name]["runtime_id"];
            $value[$itemId] = $runtimeId;
            $value2[$runtimeId] = $itemId;
            $property->setValue(ItemTranslator::getInstance(), $value);
            $property2->setValue(ItemTranslator::getInstance(), $value2);
        }

        $property->setValue(ItemTranslator::getInstance(), $value);
        $property2->setValue(ItemTranslator::getInstance(), $value2);
        
        $item = new Spyglass();
        ItemFactory::getInstance()->register($item, true);
        CreativeInventory::getInstance()->add($item);
    }
}
