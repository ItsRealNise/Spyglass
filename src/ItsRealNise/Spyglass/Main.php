<?php

namespace ItsRealNise\Spyglass;

use pocketmine\inventory\CreativeInventory;
use pocketmine\item\ItemFactory;
use pocketmine\network\mcpe\convert\ItemTranslator;
use pocketmine\plugin\PluginBase;
use pocketmine\item\StringToItemParser;
use ReflectionClass;
use const pocketmine\BEDROCK_DATA_PATH;

use ItsRealNise\Spyglass\item\Spyglass;

class Main extends PluginBase {
    
    public function onLoad() : void{
        $this->initItems();
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
        self::register(true);
    }
    
    public static function register(bool $creative = false): bool{
        $item = new Spyglass();
        $name = $item->getVanillaName();

        if($name !== null && StringToItemParser::getInstance()->parse($name) === null) StringToItemParser::getInstance()->register($name, fn() => $item);
        if($creative && !CreativeInventory::getInstance()->contains($item)) CreativeInventory::getInstance()->add($item);
        return true;
    }
}
