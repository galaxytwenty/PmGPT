<?php
namespace GLX20\PmGPT;

use GLX20\PmGPT\task\GPTResponseTask;
use pocketmine\command\Command;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\command\CommandSender;
use jojoe77777\FormAPI\CustomForm;

class Main extends PluginBase{
    private static Main $instance;
    protected Config $config;
    public string $response;

    public function onEnable() :void {
        self::$instance = $this;
        $this->saveResource("config.yml");
        $this->config = new Config($this->getDataFolder() . "config.yml", 2);
    }

    public static function getInstance() : Main {
        return self::$instance;
    }

    public function onCommand(CommandSender $sender, Command $cmd, String $label, Array $args) : bool
    {
        switch ($cmd->getName()) {
            case "chatgpt":
                if ($sender instanceof Player) {
                    if ($sender->hasPermission("pmgpt.use")) {
                        $form = new CustomForm(function (Player $player, $data) {
                            if ($data === null) {
                                return false;
                            }
                            if($this->config->get("OpenAiApiKey") === ""){
                                $player->sendMessage("§cNo valid API-key set in config.yml");
                            }
                            $filepath = $this->getDataFolder() . "temp/".$player->getName()."_chat.txt";
                            $question = $data[0];
                            $this->getServer()->getAsyncPool()->submitTask(new GPTResponseTask($question, $this->config, $player->getName(), $filepath));
                            $player->sendMessage("§4ChatGPT: §aPlease wait while I generate a response...");
                            return false;
                        });
                        $form->setTitle("§l§2[ §aPm§4GPT§r §l§2]");
                        $form->addInput("§3Whats your question ?\n");
                        $form->sendToPlayer($sender);
                    }

                }
        }
        return false;
    }
}
