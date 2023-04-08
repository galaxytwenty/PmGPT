<?php
namespace PmGPT;


use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\ClosureTask;
use pocketmine\utils\Config;


class Main extends PluginBase implements Listener {

    protected Config $config;
    protected array $conversation = [];

    public function onEnable() :void {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);

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
        if(str_contains(strtolower($message), "chatgpt")) {
            $question = preg_replace('/^chatgpt\s+/i', '', $message);
            var_dump($question);
            $response = $this->getGPTResponse($question);
            $delayTicks = 2;
            $this->getScheduler()->scheduleDelayedTask(new ClosureTask(function() use ($player, $delayTicks, $response) : void {
                $player->sendMessage("§4ChatGPT:\n§a".$response);
            }), $delayTicks);

        }
    }


    function getGPTResponse($question) {
        $ch = curl_init();


        $query = implode("\n", $this->conversation) . "\nUser: $question";
        $url = 'https://api.openai.com/v1/chat/completions';

        $api_key = $this->config->get("OpenAiApiKey");

        $post_fields = array(
            "model" => "gpt-3.5-turbo",
            "messages" => array(
                array(
                    "role" => "user",
                    "content" => $query
                )
            ),
            "max_tokens" => (int)$this->config->get("maxTokens"),
            "temperature" => (int)$this->config->get("temperature")
        );

        $header  = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $api_key
        ];
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_fields));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error: ' . curl_error($ch);
        }
        curl_close($ch);

        $response = json_decode($result);
        $answer = "";
        if (!empty($response->choices[0]->message->content)) {
            $answer = trim($response->choices[0]->message->content);
            $this->conversation[] = "user:".$question."\n".$answer;
        }
        return $answer;
    }

}
