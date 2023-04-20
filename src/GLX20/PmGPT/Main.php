<?php
namespace GLX20\PmGPT;

use GLX20\PmGPT\task\GPTResponseTask;
use pocketmine\command\Command;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\command\CommandSender;
use jojoe77777\FormAPI\CustomForm;
use RuntimeException;

class Main extends PluginBase{

    public static Main $instance;
    protected Config $config;
    protected Config $initString;
    public string $response;
    public string $cainfo_path;
    private mixed $_cainfo_resource;



    public function onEnable() :void {
        self::$instance = $this;
        $this->saveResource("config.yml");
        $this->saveResource("initialPrompt.yml");
        $this->config = new Config($this->getDataFolder() . "config.yml", 2);
        $this->initString = new Config($this->getDataFolder() . "initialPrompt.yml", 2);
        $resource = $this->getResource("cacert.pem");
        $contents = stream_get_contents($resource);
        fclose($resource);
        $resource = tmpfile();
        $resource !== false || throw new RuntimeException("Failed to create a temporary file to store CA certificate");
        fwrite($resource, $contents);
        $this->cainfo_path = stream_get_meta_data($resource)["uri"];
        $this->_cainfo_resource = $resource;
    }


    public static function getInstance() : Main {
        return self::$instance;
    }

    public function onCommand(CommandSender $sender, Command $cmd, String $label, Array $args) : bool
    {
        switch ($cmd->getName()) {
            case "chatgpt":
                if ($sender instanceof Player) {
                    if (isset($args[0]) && $args[0] === "delete"){
                        if(file_exists($this->getDataFolder() . "temp/".$sender->getName()."_chat.json")){
                            unlink($this->getDataFolder() . "temp/".$sender->getName()."_chat.json");
                            $sender->sendMessage("Your previous conversation has been §cdeleted");
                            return false;
                        }else{
                            $sender->sendMessage("§cNo conversation found to delete");
                            return false;
                        }
                    }
                    if ($sender->hasPermission("pmgpt.use")) {
                        $form = new CustomForm(function (Player $player, $data) {
                            if ($data === null) {
                                return false;
                            }
                            if($this->config->get("OpenAiApiKey") === ""){
                                $player->sendMessage("§cNo valid API-key set in config.yml");
                            }
                            $filename = $player->getName() . "_chat.json";
                            $dir = $this->getDataFolder() . "temp/";
                            $filepath = $dir . $filename;
                            $question = $data[0];


                            $this->getServer()->getAsyncPool()->submitTask(new GPTResponseTask($question, $this->config, $player->getName(), $filepath, $this->cainfo_path, $this->getInitialPrompt($player)));
                            $player->sendMessage("§4ChatGPT: §aPlease wait while I generate a response...");
                            return false;
                        });
                        $form->setTitle("§l§2[ §aPm§4GPT§r §l§2]");
                        $form->addInput("§3Whats your question ?\n");
                        $form->addLabel("You can §cdelete§r your saved conversation by typing §6/chatgpt delete§r");
                        $form->sendToPlayer($sender);
                    }

                }
        }
        return false;
    }

    public function getMaxServerPlayer() : int{
        $maxPlayers = $this->getServer()->getMaxPlayers();
        return (int)$maxPlayers;
    }

    public function getOnlinePlayers() : int{
        $onlinePlayers = count($this->getServer()->getOnlinePlayers());
        return (int)$onlinePlayers;
    }

    public function getOnlinePlayerNames() : string{
        $onlinePlayerlist = $this->getServer()->getOnlinePlayers();
        $playerNames = [];
        foreach($onlinePlayerlist as $player) {
            $playerNames[] = $player->getName();
        }
        $playerList = implode(", ", $playerNames);
        return $playerList;
    }

    public function getInitialPrompt($player){
        $initString = (string)$this->initString->get("initialPrompt");
        $placeholders = [
            '{player_name}' => $player->getName(),
            '{online_players}' => $this->getOnlinePlayers(),
            '{max_online_players}' => $this->getMaxServerPlayer(),
            '{players_names_online}' => $this->getOnlinePlayerNames(),
            '{item_in_hand}' => $player->getInventory()->getItemInHand()->getName(),
            '{player_level}' => $player->getXpManager()->getXpLevel(),
            '{player_world}' => $player->getWorld()->getFolderName()
        ];
        $initPrompt = strtr($initString, $placeholders);
        return $initPrompt;
    }
    
    protected function onDisable() : void{
        fclose($this->_cainfo_resource);
        unset($this->_cainfo_resource);
    }
}
