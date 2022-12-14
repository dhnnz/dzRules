<?php

namespace dz;

use cooldogedev\libBook\LibBook;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Loader extends PluginBase
{

    public Config $config;

    /**
     * onEnable
     *
     * @return void
     */
    public function onEnable(): void
    {
        $this->saveResource("config.yml");
        $this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML, array());
        $this->getLogger()->info("plugin active");
    }

    /**
     * onCommand
     *
     * @param  mixed $sender
     * @param  mixed $command
     * @param  mixed $label
     * @param  mixed $args
     * @return bool
     */
    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
    {
        if ($sender instanceof Player)
            return false;
        if ($command->getName() == "rules") {
            $item = VanillaItems::WRITTEN_BOOK();
            $item->setTitle($this->config->get("title"));
            $item->setAuthor($this->config->get("author"));
            for ($i = 1; $i <= 50; $i++) {
                if ($i > 0)
                    $item->setPageText($i - 1, str_replace(["{player}", "{player_displayname}", "{line}"], [$sender->getName(), $sender->getDisplayName(), "\n"], $this->config->get("pages")[$i]["text"]));
                if ($i >= 50) {
                    break;
                }
            }
            LibBook::sendPreview($sender, $item);
            return true;
        }
        return true;
    }
}
