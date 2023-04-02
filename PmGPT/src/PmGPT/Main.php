<?php
namespace PmGPT;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\plugin\PluginBase;



class Main extends PluginBase implements Listener {



    public function onEnable() :void {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onPlayerChat(PlayerChatEvent $event) {
        $player = $event->getPlayer();
        $message = $event->getMessage();
        if(strpos(strtolower($message), "chatgpt") !== false) {
            $question = str_ireplace("chatgpt", "", $message);
            $response = $this->getGPTResponse($question);
            $player->sendMessage($response);
        }
    }

    public function getGPTResponse($prompt)
    {
        $url = 'https://api.openai.com/v1/chat/completions';
        $auth_token = 'Here the API-Key';
        $question = $prompt;
        $data = array(
            "model" => "text-davinci-002",
            "messages" => array(
                array(
                    "role" => "user",
                    "content" => $question
                )
            )
        );
        $payload = json_encode($data);
        $headers = array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $auth_token,
            'Content-Length: ' . strlen($payload)
        );
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        $info = curl_getinfo($ch);

        if(curl_errno($ch)){
            $dump =  curl_error($ch);
            var_dump("ERROR ".$dump);
        }
        var_dump($info);
        var_dump($result);

        curl_close($ch);
        $response = json_decode($result, true);
        var_dump($response);
        $answer = "";
        if (!empty($response['choices'][0]['message']['content'])) {
            $answer = $response['choices'][0]['message']['content'];
            var_dump($answer);
        }
        return $answer;
    }




}
