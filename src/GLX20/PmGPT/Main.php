<?php
namespace GLX20\PmGPT;


use GLX20\PmGPT\task\GPTResponseTask;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\ClosureTask;
use pocketmine\utils\Config;


class Main extends PluginBase implements Listener {
    private static Main $instance;
    protected Config $config;
    public string $response;

    public function onEnable() :void {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        self::$instance = $this;
    }

    public static function getInstance() : Main {
        return self::$instance;
    }

    public function onLoad(): void
    {
        $this->saveResource("config.yml");
        $this->config = new Config($this->getDataFolder() . "config.yml", 2);
        if($this->config->get("OpenAiApiKey") === ""){
            $this->getLogger()->error("§cNo Api-Key set....Plugin disable");
            $this->getServer()->getPluginManager()->disablePlugin($this);
        }
    }

    public function onPlayerChat(PlayerChatEvent $event) {
        $player = $event->getPlayer();
        $message = $event->getMessage();
        $playerName = $event->getPlayer()->getName();
        $filepath = $this->getDataFolder() . "temp/".$playerName."_chat.txt";
        if($player->hasPermission("pmgpt.use")){
            if(str_contains(strtolower($message), "chatgpt")) {
                $question = preg_replace('/^chatgpt\s+/i', '', $message);
                $this->getServer()->getAsyncPool()->submitTask(new GPTResponseTask($question, $this->config, $playerName, $filepath));
                $delayTicks = 2;
                $this->getScheduler()->scheduleDelayedTask(new ClosureTask(function () use ($player): void {
                    $player->sendMessage("§4ChatGPT: §aPlease wait while I generate a response...");
                }), $delayTicks);
            }
        }
    }

}
