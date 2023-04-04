<?php
namespace PmGPT;


use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\ClosureTask;
use pocketmine\utils\Config;


class Main extends PluginBase implements Listener {

protected Config $config;

    public function onEnable() :void {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
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
        if(str_contains(strtolower($message), "chatgpt")) {
            $question = preg_replace('/^chatgpt\s+/i', '', $message);
            $response = $this->getGPTResponse($question);
            $delayTicks = 2;
            $this->getScheduler()->scheduleDelayedTask(new ClosureTask(function() use ($player, $delayTicks, $response) : void {
                $player->sendMessage("§a".$response);
            }), $delayTicks);

        }
    }


    function getGPTResponse($question) {
        $temperature = 0.2;
        $url = 'https://api.openai.com/v1/completions';
        $auth_token = $this->config->get("OpenAiApiKey");
        $data = array(
            'model' => 'text-davinci-003',
            'prompt' => $question,
            'temperature' => $temperature,
            'max_tokens' => 150,
            'frequency_penalty' => 0,
            'presence_penalty' => 0.6,
        );
        $payload = json_encode($data);
        $headers = array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $auth_token,
            'Content-Length: ' . strlen($payload)
        );
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); //ONLY TEST Security warning WHY no SSL
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //ONLY TEST Security warning WHY no SSL
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($result, true);
        $answer = "";
        if (!empty($response['choices'][0]['text'])) {
            $answer = trim($response['choices'][0]['text']);
        }
        return $answer;
    }

}
