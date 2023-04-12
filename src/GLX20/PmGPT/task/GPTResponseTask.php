<?php
namespace GLX20\PmGPT\task;

use GLX20\PmGPT\Main;
use jojoe77777\FormAPI\CustomForm;
use pocketmine\player\Player;
use pocketmine\scheduler\AsyncTask;
use pocketmine\utils\Config;

class GPTResponseTask extends AsyncTask {

    protected string $question;
    protected Config $config;
    private string $playerName;
    private string $filepath;

    public function __construct($question, Config $config, $playerName, $filepath) {
        $this->question = $question;
        $this->config = $config;
        $this->playerName = $playerName;
        $this->filepath = $filepath;
    }

    public function onRun():void {
        $ch = curl_init();
        if (file_exists($this->filepath)) {
            $conversation = file_get_contents($this->filepath);
            $query = implode("\n", (array)$conversation) . "\nUser: $this->question";
        } else {
            $query = "User: $this->question";
        }

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
        var_dump($result);
        if (curl_errno($ch)) {
            $this->setResult('Error: ' . curl_error($ch));
        } else {
            $this->setResult(json_decode($result));
        }
        curl_close($ch);
    }

    public function onCompletion():void {
        $response = $this->getResult();
        if (isset($response->error) && isset($response->error->message)) {
            $message = $response->error->message;
            Main::getInstance()->getServer()->getPlayerExact($this->playerName)->sendMessage("§4ChatGPT: §a".$message);
            return;
        }

        if(!empty($response->choices[0]->message->content)) {
            $answer = trim($response->choices[0]->message->content);
            $filename = $this->playerName . "_chat.txt";
            $dir = Main::getInstance()->getDataFolder() . "temp/";
            if (!is_dir($dir)) {
                mkdir($dir);
            }

            $filepath = $dir . $filename;
            $conversation = file_exists($filepath) ? file_get_contents($filepath) : "";
            file_put_contents($filepath, $conversation . "§r§auser: $this->question\n\n§r§c" . $answer . "\n\n");
            $form = new CustomForm(function (Player $player, $data) use ($filepath) {
                if ($data === null) {
                    return false;
                }
                $question = $data[1];
                Main::getInstance()->getServer()->getAsyncPool()->submitTask(new GPTResponseTask($question, $this->config, $this->playerName, $filepath));
                Main::getInstance()->getServer()->getPlayerExact($this->playerName)->sendMessage("§4ChatGPT: §aPlease wait while i generate a response...");
                return false;
            });
            $form->setTitle("§l§2[ §aPm§4GPT§r §2]");
            $conversation = file_exists($filepath) ? file_get_contents($filepath) : "";
            $form->addLabel("Chat history:\n" . $conversation);
            $form->addInput("§3Whats your question ?\n");
            $form->sendToPlayer(Main::getInstance()->getServer()->getPlayerExact($this->playerName));
        }
    }
}


